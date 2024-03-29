<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Applications;

class ApplicationsController extends BaseController
{
  public function getAll($request, $response, $args)
  {
    $all = Applications::all();
    return $response->getBody()->write($all->toJson());
  }

  public function getById($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    try {
      $user = Applications::findOrFail($this->args['id']);
      return $this->jsonResponse($user, 200);
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  public function create($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    $input = $this->getInput();

    $duplicate = Applications::where('url', $input['url'])->first();

    if (!$duplicate) {
      if($request->getAttribute('has_errors')) {
        $code = 400;
        $errors = $request->getAttribute('errors');

        return $this->jsonResponse($errors, $code);
      }
      $input['token'] = hash('sha256', $input['url'] . PASSWORD_SECRET_KEY);
      $user = Applications::create($input);
    }
      
    return $this->jsonResponse($user, 200);
  }

  public function update($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    $input = $this->getInput();

    if($request->getAttribute('has_errors')) {
      $code = 400;
      $errors = $request->getAttribute('errors');

      return $this->jsonResponse($errors, $code);
    }

    try {
      Applications::findOrFail($this->args['id'])->update($input);
      return $this->jsonResponse($input, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }

  public function delete($request, $response, $args)
  {
    $this->setParams($request, $response, $args);
    try{
      $user = Applications::findOrFail($this->args['id'])->delete();
      return $this->jsonResponse($user, http_response_code());
    } catch (\Exception $e) {
      return $this->jsonResponse($e, 400);
    }
  }
}
