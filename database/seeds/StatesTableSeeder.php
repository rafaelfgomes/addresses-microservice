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

            foreach ($json as $value) {

                $data[] = [
        
                    'name' => $value->nome,
                    'initials' => $value->sigla,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
        
                ];
        
            }

            $state = new State();
            $state->insert($data);

        } catch (\Exception $e) {

            exit('Erro ao rodar o seed: ' . $e->getMessage());
        
        }

    }
}
