<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use PhpParser\Node\Expr\Cast\Object_;

class CrudService
{
    public function validateData(string $requestClass, $request)
    {
        $formRequest = $requestClass::createFrom($request);
        $formRequest->setContainer(app());
        $formRequest->setRedirector(app('redirect'));

        $formRequest->validateResolved();

        $data = $formRequest->validated();

        return $data;
    }
    public function createPosition(string $model, $request):Object
    {
        try{
            $position = $model::firstOrCreate($request);
            return  $position;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        $position = $model::firstOrCreate($request);
        return  $position;
    }
    public function deletePosition(string $model, $request, $attribute)
    {
        try {
            $model::where($attribute, $request)->delete();
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
    }
}
