<?php

namespace App\Controllers\Files;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

use CodeIgniter\Files\File;

class RenderImageController extends BaseController
{
	public function profile(string $imageName)
	{
		//Carpeta Imagenes
		$path = WRITEPATH."uploads".DIRECTORY_SEPARATOR."profile".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.$imageName;
		try {
			$imageContent = file_get_contents($path);
			$imageFile = new File($path);
			// choose the right mime type
			$mimeType = $imageFile->getMimeType();//'image/jpg';
			$this->response
				->setStatusCode(200)
				->setContentType($mimeType)
				->setBody($imageContent)
				->send();
		} catch (\Exception $e) {
			throw PageNotFoundException::forPageNotFound();
		}
	}
}
