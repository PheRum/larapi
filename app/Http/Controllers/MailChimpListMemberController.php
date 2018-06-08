<?php

namespace App\Http\Controllers;

use App\Exceptions\MailChimpException;
use App\Http\Resources\ListMemberResource;
use Illuminate\Http\Request;
use MailChimp;
use Symfony\Component\Routing\Annotation\Route;
use Validator;

class MailChimpListMemberController extends Controller
{
    /**
     * Get information about members in a list
     *
     * @Route("/list/{list_id}/member", requirements={"list_id" = "[A-Za-z0-9]+"})
     * @Method({"GET"})
     *
     * @param string $list_id
     * @return \App\Http\Resources\ListMemberResource
     */
    public function index($list_id)
    {
        try {
            $data = MailChimp::getData("lists/$list_id/members");
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListMemberResource($data);
    }

    /**
     * Add a new list member
     *
     * @Route("/list/{list_id}/member", requirements={"list_id" = "[A-Za-z0-9]+"})
     * @Method({"POST"})
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $list_id
     * @return \App\Http\Resources\ListMemberResource
     */
    public function store(Request $request, $list_id)
    {
        /*
         * Fake request
         * */
        $faker = $this->fakerData();
        $request->request->add($faker);
        ####

        $validator = Validator::make($request->all(), [
            'email_address' => 'required|email',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Bad Request',
                'status' => false,
            ], 400);
        }

        try {
            $data = MailChimp::request('POST', "lists/$list_id/members", [
                'body' => json_encode($request->all()),
            ]);
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListMemberResource($data);
    }

    /**
     * Get information about a specific list member
     *
     * @Route("/list/{list_id}/member/{hash}",
     *     requirements={"list_id" = "[A-Za-z0-9]+", "hash" = "[a-z0-9]+"})
     * @Method({"GET"})
     *
     * @param  string $list_id
     * @param string $hash
     * @return \App\Http\Resources\ListMemberResource
     */
    public function show($list_id, $hash)
    {
        try {
            $data = MailChimp::getData("ists/$list_id/members/$hash");
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListMemberResource($data);
    }

    /**
     * Update a list member
     *
     * @Route("/list/{list_id}/member/{hash}",
     *     requirements={"list_id" = "[A-Za-z0-9]+", "hash" = "[a-z0-9]+"})
     * @Method({"PATCH"})
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $list_id
     * @param string $hash
     * @return \App\Http\Resources\ListMemberResource
     */
    public function update(Request $request, $list_id, $hash)
    {
        /*
         * Fake request
         * */
        $faker = $this->fakerData();
        $request->request->add($faker);
        $request->request->remove('email_address');
        ####

        $validator = Validator::make($request->all(), [
            'status' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Bad Request',
                'status' => false,
            ], 400);
        }

        try {
            $data = MailChimp::request('PATCH', "lists/$list_id/members/$hash", [
                'body' => json_encode($request->all()),
            ]);
        } catch (MailChimpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => false,
            ], $e->getCode());
        }

        return new ListMemberResource($data);
    }

    /**
     * Remove a list member
     *
     * @Route("/list/{list_id}/member/{hash}",
     *     requirements={"list_id" = "[A-Za-z0-9]+", "hash" = "[a-z0-9]+"})
     * @Method({"DELETE"})
     *
     * @param  string $list_id
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function destroy($list_id, $hash)
    {
        try {
            MailChimp::request('DELETE', "lists/$list_id/members/$hash");
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

        return [
            'email_address' => $faker->email,
            'status' => $faker->randomElement(['subscribed', 'unsubscribed', 'cleaned', 'pending']),
            'language' => 'ru',
            'vip' => true,
        ];
    }
}
