<?php

namespace App\Controller;

use App\Entity\ExtraDetails;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExtraDetailsController extends Controller
{

    public function getDetailsAction(Request $request)
    {
        /** @var EntityRepository $extraDetailsRepository */
        $extraDetailsRepository = $this->getDoctrine()->getRepository(ExtraDetails::class);
        /** @var ExtraDetails[] $extraDetails */
        $extraDetails = $extraDetailsRepository->findAll();

        $view = View::create($extraDetails, Response::HTTP_OK);

        return $this->get('fos_rest.view_handler')->handle($view);
    }


    public function postDetailAction(Request $request)
    {
        $id = $request->get('id');
        $description = $request->get('description');

        if (null === $id || null === $description) {
            $view = View::create('Invalid data.', Response::HTTP_BAD_REQUEST);
            return $this->get('fos_rest.view_handler')->handle($view);
        }

        $extraDetails = new ExtraDetails();
        $extraDetails->setId($id);
        $extraDetails->setDescription($description);

        $em = $this->getDoctrine()->getManager();
        $em->persist($extraDetails);
        $em->flush();

        $view = View::create($extraDetails, Response::HTTP_CREATED);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    public function getDetailAction($id)
    {
        /** @var EntityRepository $extraDetailsRepository */
        $extraDetailsRepository = $this->getDoctrine()->getRepository(ExtraDetails::class);
        /** @var ExtraDetails $extraDetails */
        $extraDetails = $extraDetailsRepository->findOneBy(['id' => $id]);

        if (!$extraDetails instanceof ExtraDetails) {
            $view =  View::create('Not found.', Response::HTTP_NOT_FOUND);
        } else {
            $view = View::create($extraDetails, Response::HTTP_OK);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }


    public function deleteDetailAction($id)
    {
        /** @var EntityRepository $extraDetailsRepository */
        $extraDetailsRepository = $this->getDoctrine()->getRepository(ExtraDetails::class);
        /** @var ExtraDetails $extraDetails */
        $extraDetails = $extraDetailsRepository->findOneBy(['id' => $id]);

        if (!$extraDetails instanceof ExtraDetails) {
            $view = View::create('Not found.', Response::HTTP_NOT_FOUND);
            return $this->get('fos_rest.view_handler')->handle($view);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($extraDetails);
        $em->flush();

        $view = View::create('OK', Response::HTTP_OK);

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}