<?php

namespace Project\Controller;

use Project\Entity\PhoneBook;
use Project\Validator\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\AbstractQuery;

/**
 * Class PhoneBookController
 *
 * @package Project\Controller
 */
class PhoneBookController extends AbstractController
{
    /**
     * Get all phones list
     * method GET
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function phonebook(Request $request, $id)
    {
        $firstName = $request->get('firstName', '');

        $limit = abs((int)$request->get('limit', 100));
        $offset = abs((int)$request->get('offset', 0));
        $limit = ($limit) ? $limit : 100;

        $page = ($offset / $limit) + 1;

        $repository = $this->getEntityManager()->getRepository('Project\Entity\PhoneBook');

        $criteria = [];

        if($id){
            $criteria = ['id' => $id];
        }

        if($firstName){
            $criteria = ['firstNameLike' => $firstName];
        }

        $results = $repository->findPageBy($page, $limit, $criteria, ['id' => 'DESC'], AbstractQuery::HYDRATE_ARRAY);
        $phonebooks = $results->toArray();

        return new JsonResponse(['status' => 'success', 'result' => $phonebooks, 'offset' => $offset, 'limit' => $limit, 'total' => $results->getTotal()]);
    }

    /**
     * Create new phoneBook
     * method POST
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request){
        $em = $this->getEntityManager();

        $phoneBook = new PhoneBook();
        $phoneBook->handleRequest($request);

        $validator = new Validator();
        if($errors = $validator->validate($phoneBook)){
            return new JsonResponse(['status' => 'error', 'message' => implode(', ', $errors)]);
        }

        $em->persist($phoneBook);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'result' => ['id' => $phoneBook->getId()]], 201);
    }

    /**
     * Update phoneBook
     * method PUT
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Request $request){
        $id = (int)$request->get('id');
        $repository = $this->getEntityManager()->getRepository('Project\Entity\PhoneBook');

        if(!$id || !$phoneBook = $repository->find($id)){
            return new JsonResponse(['status' => 'error', 'message' => 'Phone item does not exist']);
        }

        $em = $this->getEntityManager();

        $phoneBook->handleRequest($request);

        $validator = new Validator();
        if($errors = $validator->validate($phoneBook)){
            return new JsonResponse(['status' => 'error', 'message' => implode(', ', $errors)]);
        }

        $em->persist($phoneBook);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'result' => ['id' => $phoneBook->getId()]]);
    }

    /**
     * Delete phoneBook by id
     * method DELETE
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Request $request){
        $id = (int)$request->get('id');
        $repository = $this->getEntityManager()->getRepository('Project\Entity\PhoneBook');

        if(!$id || !$phoneBook = $repository->find($id)){
            return new JsonResponse(['status' => 'error', 'message' => 'Phone item ID ' . $id . ' does not exist']);
        }

        $em = $this->getEntityManager();
        $em->remove($phoneBook);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'result' => []]);
    }
}
