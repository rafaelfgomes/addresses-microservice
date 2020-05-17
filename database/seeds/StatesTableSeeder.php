<?php

use App\State;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client(['base_uri' => 'https://servicodados.ibge.gov.br/api/v1/localidades/estados']);
        $response = $client->request('GET');

        try {
            
            $json = json_decode($response->getBody());

            $state = new State();

            foreach ($json as $value) {

                $state->updateOrCreate(
                    [ 'name' => $value->nome, 'initials' => $value->sigla ],
                    [
        
                        'name' => $value->nome,
                        'initials' => $value->sigla,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
            
                    ]
                
                );
        
            }            

        } catch (\Exception $e) {

            exit('Erro ao rodar o seed: ' . $e->getMessage());
        
        }

    }
}
