<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/organisations/all",
     *     tags={"organisation","view"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *                  type="array",
     *                  @OA\Items(
     *              @OA\Property (
     *                  property="organisation", type="object",ref="#components/schemas/Organisation"
     *              ),
     *              ),
     *          )
     *     )
     * )
     */
    public function allOrgans()
    {
        return Organisation::with('groups')->get();
    }

    /**
     * @OA\Get(
     *     path="/api/organisations",
     *     summary="Gets all the organisations that a user has joined as an admin",
     *     tags={"organisation","view"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property (
     *                      property="organisation",
     *                      type="object",
     *                      ref="#components/schemas/Organisation",
     *                  ),
     *              ),
     *         )
     *     )
     * )
     */

    public function userOrgans()
    {
        return Organisation::whereHas('admins', function ($q) {
            $q->where('id', Auth::id());
        })->get();
    }


    /**
     * @OA\Get(
     *     path="/api/organisations/not-joined",
     *     summary="Returns all the organisations the user doesn't belong to as an admin",
     *     tags={"organisation","view"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property (
     *                      property="organisation",
     *                      type="object",
     *                      ref="#components/schemas/Organisation"
     *                  ),
     *             ),
     *         ),
     *     ),
     * )
     */

    public function nonUserOrgans()
    {
        return Organisation::whereDoesntHave('admins', function ($p) {
            $p->where('id', Auth::id());
        })->get();
    }

    /**
     * @OA\Get(
     *     path="/api/organisations/{id}",
     *     summary="Returns a specific organisation, if the user is an admin",
     *     tags={"organisation","view"},
     *     @OA\Parameter(
     *         description="ID of organisation to fetch",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", readOnly="true", example="1"),
     *                  @OA\Property(property="name", type="string", readOnly="false", example="Odisee Hogeschool"),
     *                  @OA\Property(property="description", type="string", readOnly="false", example="Een hogeschool in België die deel uit maakt van de KU Leuven"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
     *                  @OA\Property(
     *                      property="Groups",
     *                      type="array",
     *                      @OA\Items(
     *                           type="object",
     *                      )
     *                  ),
     *                  @OA\Property(
     *                       property="Admins",
     *                       type="array",
     *                       @OA\Items(
     *                           type="object",
     *                       )
     *                  ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="CSRF token mismatch",
     *     ),
     * )
     */

    public function organ(Organisation $organisation)
    {
        return new OrganisationResource($organisation);
    }



    /**
     * @OA\Post(
     *     path="/api/organisations",
     *     summary="Creates a new organisation",
     *     tags={"organisation","create"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="organisation_id", type="integer", readOnly="true", example="1"),
     *                  @OA\Property(property="message", type="string", readOnly="false", example="The Organisation has been created successfully"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated/Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="CSRF token mismatch",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *     ),
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organisations'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        $organ = Organisation::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        Auth::user()->organisations()->attach($organ);
        return response(['message' => 'The Organisation has been created successfully', 'organisation_id' => $organ->id], 200);
    }


}
