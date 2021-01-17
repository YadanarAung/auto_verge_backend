<?php

namespace App\Helpers;

class Responder
{
	protected $status;
	protected $message;

	protected function outputResponse()
	{
		$data = [
			'status' => $this->status,
			'message' => $this->message
		];

		return response()->json($data, $this->status);
	}

	public function createResponse($success = true)
	{
		if ($success) {
			$this->status = 200;
			$this->message = 'Create success!';
		} else {
			$this->status = 500;
			$this->message = 'Create fail!';
		}

		return $this->outputResponse();
	}

	public function updateResponse($success = true)
	{
		if ($success) {
			$this->status = 200;
			$this->message = 'Update success!';
		} else {
			$this->status = 500;
			$this->message = 'Update fail!';
		}

		return $this->outputResponse();
	}

	public function deleteResponse($success = true)
	{
		if ($success) {
			$this->status = 200;
			$this->message = 'Delete success!';
		} else {
			$this->status = 500;
			$this->message = 'Delete fail!';
		}

		return $this->outputResponse();
	}

	public function uniqueResponse($isUnique = true)
	{
		if ($isUnique) {
			$this->status = 200;
			$this->message = 'Unique!';
		} else {
			$this->status = 409;
			$this->message = 'Not unique!';
		}

		return $this->outputResponse();
	}

	public function noDataResponse()
	{
		$this->status = 204;
		$this->message = 'No data!';

		return $this->outputResponse();
	}

	public function unauthenticatedResponse()
	{
		$this->status = 401;
		$this->message = "Unauthenticated access!";

		return $this->outputResponse();
	}

	public function deniedResponse()
	{
		$this->status = 403;
		$this->message = "Access denied!";

		return $this->outputResponse();
	}

	public function customResponse($status, $message)
	{
		$this->status = $status;
		$this->message = $message;
		return $this->outputResponse();
	}
}