<?php

namespace App\Http\Controllers;

use App\Exceptions\MailChimpException;
use App\Http\Resources\ListResource;
use MailChimp;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Annotation\Route;
use Validator;

class MailChimpListController extends Controller
{
    /**
     * Get information about all lists
     *
     * @Route("/list")
     * @Method({"GET"})
     *
     * @return \App\Http\Resources\ListResource
     */
    public function index()
    {
        try {
            $data = MailChimp::getData('lists');
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListResource($data);
    }

    /**
     * Create a new list
     *
     * @Route("/list")
     * @Method({"POST"})
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\ListResource
     */
    public function store(Request $request)
    {
        /*
         * Fake request
         * */
        $faker = $this->fakerData();
        $request->request->add($faker);
        ####

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact.company' => 'required|string',
            'contact.address1' => 'required|string',
            'contact.address2' => 'string',
            'contact.city' => 'required|string',
            'contact.state' => 'required|string',
            'contact.zip' => 'required|string',
            'contact.country' => 'required|string',
            'permission_reminder' => 'required|string',
            'campaign_defaults.from_name' => 'required|string',
            'campaign_defaults.from_email' => 'required|email',
            'campaign_defaults.subject' => 'required|string',
            'campaign_defaults.language' => 'required|string',
            'email_type_option' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Bad Request',
                'status' => false,
            ], 400);
        }

        try {
            $data = MailChimp::request('POST', 'lists', [
                'body' => json_encode($request->all()),
            ]);
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListResource($data);
    }

    /**
     * Get information about a specific list
     *
     * @Route("/lists/{list_id}", requirements={"list_id" = "[A-Za-z0-9]+"})
     * @Method({"GET"})
     *
     * @param  string $list_id
     * @return \App\Http\Resources\ListResource
     */
    public function show($list_id)
    {
        try {
            $data = MailChimp::getData('lists/' . $list_id);
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListResource($data);
    }

    /**
     * Update a specific list
     *
     * @Route("/lists/{list_id}", requirements={"list_id" = "[A-Za-z0-9]+"})
     * @Method({"PATCH"})
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $list_id
     * @return \App\Http\Resources\ListResource
     */
    public function update(Request $request, $list_id)
    {
        /*
         * Fake request
         * */
        $faker = $this->fakerData();
        $request->request->add($faker);
        ####

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact.company' => 'required|string',
            'contact.address1' => 'required|string',
            'contact.address2' => 'string',
            'contact.city' => 'required|string',
            'contact.state' => 'required|string',
            'contact.zip' => 'required|string',
            'contact.country' => 'required|string',
            'permission_reminder' => 'required|string',
            'campaign_defaults.from_name' => 'required|string',
            'campaign_defaults.from_email' => 'required|email',
            'campaign_defaults.subject' => 'required|string',
            'campaign_defaults.language' => 'required|string',
            'email_type_option' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Bad Request',
                'status' => false,
            ], 400);
        }

        try {
            $data = MailChimp::request('PATCH', 'lists/' . $list_id, [
                'body' => json_encode($request->all()),
            ]);
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListResource($data);
    }

    /**
     * Delete a list
     *
     * @Route("/lists/{list_id}", requirements={"list_id" = "[A-Za-z0-9]+"})
     * @Method({"DELETE"})
     *
     * @param  string $list_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($list_id)
    {
        try {
            MailChimp::request('DELETE', 'lists/' . $list_id);
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return response(null, 204);
    }

    /**
     * @return array
     */
    protected function fakerData()
    {
        $faker = \Faker\Factory::create();

        $data = [
            'name' => $faker->name,
            'contact' => [
                'company' => $faker->company,
                'address1' => $faker->address,
                'address2' => $faker->address,
                'city' => $faker->city,
                'state' => $faker->countryCode,
                'zip' => $faker->postcode,
                'country' => $faker->countryCode,
            ],
            'permission_reminder' => 'permission_reminder',
            'campaign_defaults' => [
                'from_name' => $faker->name,
                'from_email' => $faker->email,
                'subject' => $faker->title,
                'language' => $faker->languageCode,
            ],
            'email_type_option' => true,
        ];

        return $data;
    }
}
