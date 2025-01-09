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

$subdomain = 'irinagalichs'; 
//Долгосрочный токен
$accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjcwOTAxZDg2NTBmYjA3Zjk4YTNlMGEyMGJhNTkyMmQyNWEzODdkZjhmMTVjNWNiNTIzOGFhYWZiMDM3NzYwYjQ1OTM1NmJlNTI5NjI0ZDJmIn0.eyJhdWQiOiI0NTU3NzFhMy1hMjIwLTRlZTMtYjY2Yy1lMDljYzAwZjgwZGQiLCJqdGkiOiI3MDkwMWQ4NjUwZmIwN2Y5OGEzZTBhMjBiYTU5MjJkMjVhMzg3ZGY4ZjE1YzVjYjUyMzhhYWFmYjAzNzc2MGI0NTkzNTZiZTUyOTYyNGQyZiIsImlhdCI6MTczNjI3OTIwMiwibmJmIjoxNzM2Mjc5MjAyLCJleHAiOjE3MzgzNjgwMDAsInN1YiI6IjExOTQyNjU0IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMyMTQ1MDA2LCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiOTNiOTM3YzEtOWI4OS00MGUxLTk0MWItY2JjODk4MjRiM2RjIiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.nELT97OIzfdtp2Nh9VSji28i4d8Z7IAOeDn5zBScV93nwALyGX8Tbp85hrGvmxrXg5Vwo4H18jkIi_Mlow_FU6r-mzxPqopX7ROwwSA3nKzJVzJSMAbT_qRZziNOZ6kZZp03xQhcXi5C7jSKUUuVSViL5cc2R-n6WAfosDMeHfGkDe226rmfLNKY8r4xQINoYC807GmWCb7Ya2r27sC_9FuZNlflAw2QwMscagbNmi40cy67Z24uuDhINyYb4ME3zQmqyVyEetW3n78FDhgDrBJfCa0AmQx9Nt-hp9dPe-c3da8WYNHCGRQPKNOnS-b7eAybi7tpt3rdEYFUV5arvw';
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
                                            ->setFieldId('671811')
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
