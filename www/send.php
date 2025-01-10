<?php

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;

require 'vendor/autoload.php';

$subdomain = 'g1rin3'; 
//Долгосрочный токен
$accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE2Mjc4YzEzOTg1MjlmNTk2NTg4YjAwNTgzMDg5OTRhYzdmODk3NWNiNDlkY2UyZWY1YmU2YjRjMTRmZDRlMTMxZWJkZDE1OTk4MWNkMzg0In0.eyJhdWQiOiI1NTkxMmJiNS0wNDdiLTQ1OWUtODdhMS05MGI0MzgzNWViOTkiLCJqdGkiOiJhNjI3OGMxMzk4NTI5ZjU5NjU4OGIwMDU4MzA4OTk0YWM3Zjg5NzVjYjQ5ZGNlMmVmNWJlNmI0YzE0ZmQ0ZTEzMWViZGQxNTk5ODFjZDM4NCIsImlhdCI6MTczNjQ3MDk2MCwibmJmIjoxNzM2NDcwOTYwLCJleHAiOjE3MzgzNjgwMDAsInN1YiI6IjExOTY2MDE4IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMyMTU5NzM4LCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiYTBjZGQ3MzgtMTU2Ny00YzNhLThlMTctMTcyYzc0ODVkZTIyIiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.f4Z8mV9RzuYKgJhvr8AfIYNuebUN5wq_1nmDsPuOjZH48Zx5ZtyQ-xcRGpvwPlLeRJO0AygWMMZ3fEzT83bgiMbASwcIsWBvEa2aCwbxaRSAN0BoOijiwC_Eb5e6BbS1-WilvoD1vA12ysH2ONKSDIC_pLrA4mYXgrZwL7Yy1mRZgv53SfdSauXvTUJSgLN_OvmlmgnTTTfDjQk-uAAqlkROjTt60n2s5Rb5JPYKiSd8q_hxUtuUFrF3dqWfw5FcTKOyFlENP3i-ITWlmlavKgl5NnGVXbX5ZQe_3zq0pw9nQGu7cNL8Ea3V8zYLS4htwasAR6ZGGhcXhbP6FfwTkg';
$apiClient = new AmoCRMApiClient();
$longLivedAccessToken = new LongLivedAccessToken($accessToken);
$apiClient->setAccessToken($longLivedAccessToken)
    ->setAccountBaseDomain("$subdomain.amocrm.ru");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $externalData = [
        [
            'price' => filter_input(INPUT_POST, 'price'),
            'contact' => [
                'first_name' => filter_input(INPUT_POST, 'name'),
                'email' => filter_input(INPUT_POST, 'email'),
                'phone' => filter_input(INPUT_POST, 'phone'),
                'over30' => filter_input(INPUT_POST, '30Second'),
            ],

        ],
    ];

    $leadsCollection = new LeadsCollection();

    foreach ($externalData as $externalLead) {
        $lead = (new LeadModel())
            //->setName('Название сделки')
            ->setPrice($externalLead['price'])
            ->setContacts(
                (new ContactsCollection())
                    ->add(
                        (new ContactModel())
                            ->setFirstName($externalLead['contact']['first_name'])
                            ->setCustomFieldsValues(
                                (new CustomFieldsValuesCollection())
                                    ->add(
                                        (new MultitextCustomFieldValuesModel())
                                            ->setFieldCode('PHONE')
                                            ->setValues(
                                                (new MultitextCustomFieldValueCollection())
                                                    ->add(
                                                        (new MultitextCustomFieldValueModel())
                                                            ->setValue($externalLead['contact']['phone'])
                                                    )
                                            )
                                    )
                                    ->add(
                                        (new MultitextCustomFieldValuesModel())
                                            ->setFieldCode('EMAIL')
                                            ->setValues(
                                                (new MultitextCustomFieldValueCollection())
                                                    ->add(
                                                        (new MultitextCustomFieldValueModel())
                                                            ->setValue($externalLead['contact']['email'])
                                                    )
                                            )
                                    )
                                    ->add(
                                        (new TextCustomFieldValuesModel())
                                            ->setFieldId('787041')
                                            ->setValues(
                                                (new TextCustomFieldValueCollection())
                                                    ->add(
                                                        (new TextCustomFieldValueModel())
                                                            ->setValue($externalLead['contact']['over30'])
                                                    )
                                            )
                                    )
                            )
                    )
            );
        $leadsCollection->add($lead);
    }

    try {
        $addedLeadsCollection = $apiClient->leads()->addComplex($leadsCollection);
        include('index.php');
    } catch (AmoCRMApiException $e) {
        var_dump($e);
        die;
    }
}
