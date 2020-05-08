<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\Http\Resources\Address as AddressResource;
use App\Neighborhood;
use App\State;
use App\Traits\ApiResponser;
use App\Zipcode;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddressController extends Controller
{

    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create an resource.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     *
     */
    public function findOrCreate($zip)
    {

        $relations = 'address.neighborhood.city.state';
        $zipcode = $this->checkIfExists($zip);

        if ($zipcode) {

            $fullAddress = new AddressResource($zipcode->with($relations)->first());

        } else {

            $client = new Client();

            $cep = strpos($zip, '-') ? str_replace('-', '', $zip) : $zip;

            try {
                $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                return $this->errorResponse('CEP inválido', $e->getCode());
            }
    
            $json = json_decode($response->getBody());

            if (isset($json->erro)) {
                return $this->errorResponse('CEP não existe', Response::HTTP_BAD_REQUEST);
            }

            try {

                $state = State::where('initials', $json->uf)->first();           

                $city = City::where('name', $json->localidade)->where('state_id', $state->_id)->first();

                if (!$city) {
                    $cityData = [
                        'name' => $json->localidade,
                        'ibge_code' => $json->ibge,
                        'state_id' => $state->_id
                    ];

                    $c = new City();
        
                    $newCity = $c->create($cityData);
                    $cityId = $newCity->_id;
                } else {
                    $cityId = $city->_id;
                }

                $neighborhood = Neighborhood::where('name', $json->bairro)->where('city_id', $cityId)->first();
    
                if (!$neighborhood) {
                    $neighborhoodData = [
                        'name' => $json->bairro,
                        'city_id' => $cityId
                    ];

                    $n = new Neighborhood();
        
                    $newNeighborhood = $n->create($neighborhoodData);
                    $neighborhoodId = $newNeighborhood->_id;
                } else {
                    $neighborhoodId = $neighborhood->_id;
                }

                $address = Address::where('name', $json->logradouro)->where('neighborhood_id', $neighborhoodId)->first();

                if (!$address) {
                    $addressData = [
                        'name' => $json->logradouro,
                        'complement' => $json->complemento,
                        'neighborhood_id' => $neighborhoodId
                    ];

                    $a = new Address();
        
                    $newAddress = $a->create($addressData);
                    $addressId = $newAddress->_id;
                } else {
                    $addressId = $address->_id;
                }
    
                $zipcodeData = [
                    'number' => str_replace('-', '', $json->cep),
                    'address_id' => $addressId
                ];

                $z = new Zipcode();
                $newZipcode = $z->create($zipcodeData);
    
            } catch (\Exception $e) {

                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
            
            }

            $fullAddress = new AddressResource(Zipcode::where('number', $newZipcode->number)->with($relations)->first());

        }

        return $this->successResponse($fullAddress);

    }

    /**
     * Check if a zipcode exists.
     *
     * @param  string $zipcode
     * @return bool
     *
     */
    public function checkIfExists($zipcode)
    {
        $zipcode = Zipcode::where('number', $zipcode);
        return ($zipcode->get()->isEmpty()) ? false : $zipcode;        
    }

}
