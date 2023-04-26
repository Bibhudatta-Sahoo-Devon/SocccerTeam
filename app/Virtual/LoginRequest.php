<?php


namespace App\Virtual;



/**
 * @OA\Schema(
 *      title="User Login request",
 *      description="validate login request body data",
 *      type="object",
 *      required={"email","password"}
 * )
 */
class LoginRequest
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="Email id of the user",
     *      example="admin@soccer.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Password",
     *      description="User's Password",
     *      example="admin@123"
     * )
     *
     * @var string
     */
    public $password;


}
