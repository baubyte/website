<?php

namespace App\Controllers\Front;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\SkillModel;
use App\Models\ProfileModel;
use App\Models\ExperienceModel;
use App\Controllers\BaseController;
use App\Models\StudyModel;

class HomeController extends BaseController
{

	/**
	 * Metodo de la vista principal Front/Landing
	 *
	 * @return view
	 */
	public function index()
	{
		//Cargamos el helper de 'reCaptcha'
		helper('reCaptcha');
		//Profile Model
		$profileModel = new ProfileModel();
		$profile = $profileModel->first();
		//Skill Model
		$skillModel = new SkillModel();
		$skills = $skillModel->findAll();
		//Experience Model
		$experienceModel = new ExperienceModel();
		$experiences = $experienceModel->orderBy('start', 'DESC')->findAll();
		//Study Model
		$studyModel = new StudyModel();
		$studies = $studyModel->orderBy('start','DESC')->findAll();
		//Cargamos la vista principal
		return view('Front/home', ['profile' => $profile, 'skills' => $skills, 'experiences' => $experiences, 'studies' => $studies]);
	}
	/**
	 * Envió de Email de Contacto
	 *
	 * @return void
	 */
	public function contact()
	{
		//Validamos los datos
		if ($this->validate('contactEmail') === false) {
			//Retornamos la lista de errores
			return redirect()->to(site_url('#contact'))->withInput()->with('errors', $this->validator->getErrors());
		}
		//Recibimos la informacion
		$name = $this->request->getPost('name');
		$from = $this->request->getPost('email');
		$subject = $this->request->getPost('subject');
		$message = $this->request->getPost('message');
		//Aramos un array con los datos para pasarla a la vista
		$dataView = [
			'name' => $name,
			'email' => $from,
			'subject' => $subject,
			'message' => $message,
		];
		//Cargamos la vista para ser enviada en el mail
		$viewEmailContact = view('Front/emails/email_contact', $dataView);

		//Cargamos el servicio de Email
		$email = service('email');

		//Seteamos para que email va
		$email->setTo('paredbaez.martin@gmail.com');
		//Setemamos quien nos contacta
		$email->setFrom($from, $name);
		//Seteamos el asunto
		$email->setSubject($subject);
		//Seteamos el mensaje
		$email->setMessage($viewEmailContact);
		//Comprobamos si se envió el mail
		if ($email->send()) {
			return redirect()->to(site_url('#contact'))->with('success', lang('App.success_contact'));
		} else {
			return redirect()->to(site_url('#contact'))->withInput()->with('error', lang('App.error_contact'));
		}
	}

	/**
	 * Genera CV en PDF
	 *
	 * @return void
	 */
	public function generatePDF()
	{

		$options = new Options();
		$options->set('isRemoteEnabled',true);      
		$options->set('defaultPaperSize','A4'); 
		$options->set('defaultPaperOrientation','portrait'); 
		$options->set('isHtml5ParserEnabled', true); 
		$options->set('dpi', 120); 
		$dompdf = new Dompdf( $options );

		$profileModel = new ProfileModel();
		$profile = $profileModel->first();
		//Skill Model
		$skillModel = new SkillModel();
		$skills = $skillModel->findAll();
		//Experience Model
		$experienceModel = new ExperienceModel();
		$experiences = $experienceModel->orderBy('start', 'DESC')->findAll();
		//Study Model
		$studyModel = new StudyModel();
		$studies = $studyModel->orderBy('start','DESC')->findAll();
		$html = view('Front/pdf/cv', ['profile' => $profile, 'skills' => $skills, 'experiences' => $experiences, 'studies' => $studies]);
		$dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream('paredBaezMartinCV.pdf');
	}
}
