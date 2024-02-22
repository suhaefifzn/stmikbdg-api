<?php

namespace App\Http\Controllers\Users;

use App\Exceptions\ErrorHandler;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// ? Models - table
use App\Models\Users\Site;
use App\Models\Users\User;
use App\Models\Users\UserSite;

// ? Models - view
use App\Models\Users\UserSitesView;

class SiteController extends Controller
{
    public function getAllSites(Request $request) {
        try {
            $siteId = $request->query('site_id');

            if ($siteId) {
                $tempSiteUsers = UserSitesView::where('site_id', $siteId)->get();
                $siteUsers = [];

                foreach ($tempSiteUsers as $index => $item) {
                    $userEmail = User::where('id', $item['user_id'])->first()['email'];
                    $item['user_email'] = $userEmail;
                    $siteUsers[$index] = $item;
                }

                return $this->successfulResponseJSON([
                    'site_users' => array_values($siteUsers),
                ]);
            }

            $sites = Site::all();

            return $this->successfulResponseJSON([
                'sites' => $sites,
            ]);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function postUserSite(Request $request) {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'site_id' => 'required|exists:sites,id',
            ]);

            UserSite::insert([
                'user_id' => $request->user_id,
                'site_id' => $request->site_id,
            ]);

            return $this->successfulResponseJSON([
                'user_id' => $request->user_id,
                'site_id' => $request->site_id,
            ], 'Akses user ke web berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function postNewSite(Request $request) {
        try {
            $request->validate([
                'url' => 'required|string',
            ]);

            $validatedURL = filter_var($request->url, FILTER_VALIDATE_URL);

            Site::insert([
                'url' => $validatedURL
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Url site berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }

    public function deleteUserSiteAccess(Request $request) {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            UserSite::where('id', $request->id)->delete();

            return $this->successfulResponseJSON([
                'id' => $request->id
            ], 'Akses user ke url berhasil dihapus');
        } catch (\Exception $e) {
            return ErrorHandler::handle($e);
        }
    }
}
