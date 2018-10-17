<?php
/* Copyright (c) 2018-2019, Fares Abdullah, all rights reserved. */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\Query\ResultSetMapping;

use GuzzleHttp\Client as GuzzleClient;

use App\Teamup\Colors as TeamupColors;
use App\Entity\Guest;

class GiglioController extends AbstractController {

    /**
     * @Route("/", name="index")
     */
    public function index() {
	$message = "";
/*	$entityManager = $this->getDoctrine()->getManager();
	$message = $entityManager === null ? "doctrine manager is null" : "got doctrine manager!";
	$message .= " ";
	//$sql = "CREATE TABLE memos (id INT, name TEXT)";
	$sql = "INSERT INTO memos VALUES (1, \"abc\")";
	$rsm = new ResultSetMapping();
	$query = $entityManager->createNativeQuery($sql, $rsm);
	$result = $query->getResult();
	$message .= $result === null ? "result is null" : "got result!";
 */

	$client = new GuzzleClient(['headers' => ['Teamup-Token' => '8d3787df2bc1a35ea3c6a2a61780c87f716e1290fc354c06d96230f31d4b7277']]);
/*
	$result = $client->get('https://api.teamup.com/ksra2p4hb2g2dhfvu6/events?startDate=2018-01-01&endDate=2018-12-31');

	$message .= "events<br>";
	$statusCode = $result->getStatusCode();
	$message .= "$statusCode<br>";

	if ($statusCode == "200") {
		$message .= "<br>";
		$message .= "<br>";
		$message .= $result->getHeader('content-type')[0];
		$message .= "<br>";
		$message .= "<br>";
		$message .= "<pre>" . json_encode(json_decode($result->getBody()), JSON_PRETTY_PRINT) . "</pre>";
		//$message .= "<pre>" . $result->getBody() . "</pre>";
	}
 */
	$result = $client->get('https://api.teamup.com/ksra2p4hb2g2dhfvu6/subcalendars');

	$message .= "subcalendars<br>";
	$statusCode = $result->getStatusCode();
	$message .= "$statusCode<br>";

	$subcalendars = [];
	if ($statusCode == "200") {
		$message .= "<br>";
		$message .= "<br>";
		$message .= $result->getHeader('content-type')[0];
		$message .= "<br>";
		$message .= "<br>";
//		$message .= "<pre>" . json_encode(json_decode($result->getBody()), JSON_PRETTY_PRINT) . "</pre>";

		$subcalendars_full = json_decode($result->getBody(), true);
		ob_start();
		var_dump($subcalendars_full);
		$message .= "<pre>" . ob_get_contents() . "</pre>";
		ob_end_clean();

		foreach ($subcalendars_full['subcalendars'] as $subcalendar) {
			if ($subcalendar['active']) {
				$subcalendars[] = array(
					"color" => TeamupColors::getColor($subcalendar["color"]),
					"name" => $subcalendar["name"],
				);
			}
		}
		usort($subcalendars, function($a, $b) {
			return $a['name']<$b['name'];
		});

		//$message .= "<pre>" . $result->getBody() . "</pre>";
	}
/*
	$result = $client->get('https://api.teamup.com/ksra2p4hb2g2dhfvu6/keys');

	$message .= "keys<br>";
	$statusCode = $result->getStatusCode();
	$message .= "$statusCode<br>";

	if ($statusCode == "200") {
		$message .= "<br>";
		$message .= "<br>";
		$message .= $result->getHeader('content-type')[0];
		$message .= "<br>";
		$message .= "<br>";
		$message .= "<pre>" . json_encode(json_decode($result->getBody()), JSON_PRETTY_PRINT) . "</pre>";
		//$message .= "<pre>" . $result->getBody() . "</pre>";
	}
 */
        return $this->render('giglio/index.html.twig', [
//            'message' => $message,
            'message' => "",
	    'teamupReadonlyKey' => 'ks7wvzdu9izk4er27n',
	    'subcalendars' => $subcalendars,
        ]);
    }

    /**
     * @Route("/guests", name="guests")
     */
    public function guests() {
	$guests = $this->getDoctrine()->getRepository(Guest::class)->findAll();
	return $this->render('guests/index.html.twig', [
		"guests" => $guests,
	]);
    }

    private function guest_form(): Form {
	    return null;
    }

    /**
     * @Route("/guests/new", name="new_guest", methods={"GET", "POST"})
     */
    public function new_guest(Request $request) {
	$guest = new Guest();

	$formbuilder = $this->createFormBuilder($guest);
	$formbuilder->add('name', TextType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('email', EmailType::class, array('required' => false, 'attr' => array('class' => 'form-control')));
	$formbuilder->add('phone', TelType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('address', TextareaType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('notes', TextareaType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')));
	$form = $formbuilder->getForm();

	$form->handleRequest($request);
	
	if ($form->isSubmitted() && $form->isValid()) {
		$guest = $form->getData();
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($guest);
		$entityManager->flush();

		return $this->redirectToRoute('guests');
	}


	return $this->render('guests/new.html.twig', [
		'form' => $form->createView(),
	]);
    }

    /**
     * @Route("/guests/edit/{id}", name="edit_guest")
     */
    public function edit_guest(Request $request, $id) {
	$guest = $this->getDoctrine()->getRepository(Guest::class)->find($id);

	$formbuilder = $this->createFormBuilder($guest);
	$formbuilder->add('name', TextType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('email', EmailType::class, array('required' => false, 'attr' => array('class' => 'form-control')));
	$formbuilder->add('phone', TelType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('address', TextareaType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('notes', TextareaType::class, array('attr' => array('class' => 'form-control')));
	$formbuilder->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')));
	$form = $formbuilder->getForm();

	$form->handleRequest($request);
	
	if ($form->isSubmitted() && $form->isValid()) {
//		$guest = $form->getData();
		$entityManager = $this->getDoctrine()->getManager();
//		$entityManager->persist($guest);
		$entityManager->flush();

		return $this->redirectToRoute('guests');
	}


	return $this->render('guests/edit.html.twig', [
		'form' => $form->createView(),
		'guest' => $guest,
	]);
    }
}
