<?php
/**
 * Author : Sunu Aziz Rahayu
 * URL    : https://github.com/sunuazizrahayu/codeigniter3-cloudinary
 */

defined('BASEPATH') OR exit('No direct script access allowed');

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class Cloudinary
{
	protected $ci;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->config->load('cloudinary', true);


		Configuration::instance([
			'cloud' => [
				'cloud_name' 	=> $this->ci->config->item('cloudinary_cloud_name'),
				'api_key' 		=> $this->ci->config->item('cloudinary_api_key'),
				'api_secret' 	=> $this->ci->config->item('cloudinary_api_secret'),
			],
			'url' => ['secure' => true]
		]);
	}

	public function upload($file, $options=[])
	{
		$response = (new UploadApi())->upload($file, $options);
		return $response;
	}

	public function delete($file_url, $options=[])
	{
		//get file path by file url
		$pattern = "/^https:\/\/([a-z0-9\\.]+\/){5}/i";
		$file = preg_replace($pattern, "", $file_url);
		$file = substr($file, 0, strrpos($file, '.'));
		$file = urldecode($file);

		//delete
		$options['invalidate'] = true; //bypass caching
		$response = (new UploadApi())->destroy($file, $options);
		return $response;
	}

}

/* End of file Cloudinary.php */
