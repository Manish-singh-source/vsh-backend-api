
# VSH Backend API




# Main goals 

1. login 
2. register 
3. refresh token 
4. logout 
5. forgot password 
6. reset password 
7. change password 
8. Dashboard 

9. profile 
10. update profile 
11. Delete account 
12. Upload Profile Image 
13. Approval Pending 
14. Approve Request 
15. Reject Request 
16. Family Member List 
17. Add Family Member 
18. Add Booking 









Users 
    - id
    - role 
    - user_id 
    - full_name 
    - phone_number 
    - email 
    - wing_no 
    - flat_no 
    - password 
    - profile_image 
    - otp 
    - otp_expires_at 
    - is_verified 
    - qr_code_image
    - status - (active, inactive, blocked, suspended)
    - approved_by 
    - approved_at 
    - created_at 
    - updated_at 
    - deleted_at 


















1. role based login : 
i. roles: owner, staff, admin, super admin, owner family member, owner rental person, owner rental family member 
ii. when registering using prefixes for user_id such as: 
    # formula: role (first 2 letters) + Wing name + 0001 
    - owner - OWA001 
    - staff - ST001 
    - admin - AD001 
    - super admin - SA001 
    - owner family member - OWAF001 
    - owner rental person - OWAR001 
    - owner rental family member - OWARF001 

2. unique user_id 
3. phone_number unique 
4. email unique 
5. wing_name & flat_no both are unique 
6. otp based login system 
7. after registratin want to generate qr code using user_id, role and currently static - membership details: as from date, to date, title. 
8. Note: currently we are using "spatie/laravel-permission": "^6.24" for role based authentication. 

following are my db requirements: 

Users 
    - id
    - role 
    - user_id 
    - full_name 
    - phone_number 
    - email 
    - wing_name
    - flat_no 
    - password 
    - profile_image 
    - otp 
    - otp_expires_at 
    - is_verified 
    - qr_code_image
    - status - (active, inactive, blocked, suspended)
    - approved_by 
    - approved_at 
    - created_at 
    - updated_at 
    - deleted_at 


following is my actual migration file: 

public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique(); 
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('wing_name');
            $table->string('flat_no');
            $table->string('profile_image')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('qr_code_image');
            $table->enum('status', ['active', 'inactive', 'blocked', 'suspended'])->default('inactive');
            $table->string('password');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }


Now i want to make APIs for this: 

endpoints: 
    1. /api/v1/auth/login 
        - login_id = user_id
    2. /api/v1/auth/register 
    3. /api/v1/auth/refresh 
    4. /api/v1/auth/logout 
    5. /api/v1/forgot-password 
    6. /api/v1/reset-password 
    7. /api/v1/change-password 
    8. /api/v1/dashboard 


{
	"info": {
		"_postman_id": "aae57670-6c6c-4afb-94b8-4f792cb3dd4e",
		"name": "Society API",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "26252000"
	},
	"item": [
		{
			"name": "Login",
			"event": [
				{
					"listen": "test",
					"script": {
						"exec": [
							"let json = pm.response.json();\r",
							"\r",
							"if (json.token) {\r",
							"    pm.environment.set(\"token\", json.token);\r",
							"}"
						],
						"type": "text/javascript",
						"packages": {},
						"requests": {}
					}
				}
			],
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "formdata",
					"formdata": [
						{
							"key": "login_id",
							"value": "AD0001",
							"type": "text"
						},
						{
							"key": "password",
							"value": "Admin@123",
							"type": "text"
						}
					]
				},
				"url": {
					"raw": "{{apiUrl}}/api/v1/auth/login",
					"host": [
						"{{apiUrl}}"
					],
					"path": [
						"api",
						"v1",
						"auth",
						"login"
					]
				}
			},
			"response": []
		},
		{
			"name": "Register",
			"event": [
				{
					"listen": "test",
					"script": {
						"exec": [
							"let json = pm.response.json();\r",
							"\r",
							"if (json.token) {\r",
							"    pm.environment.set(\"token\", json.token);\r",
							"}"
						],
						"type": "text/javascript",
						"packages": {},
						"requests": {}
					}
				}
			],
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "formdata",
					"formdata": [
						{
							"key": "full_name",
							"value": "Gaurav",
							"type": "text"
						},
						{
							"key": "email",
							"value": "gauravsadvelkar31@gmail.com",
							"type": "text"
						},
						{
							"key": "phone",
							"value": "7898767861",
							"type": "text"
						},
						{
							"key": "wing_name",
							"value": "A",
							"type": "text"
						},
						{
							"key": "flat_no",
							"value": "101",
							"type": "text"
						},
						{
							"key": "password",
							"value": "123456",
							"type": "text"
						},
						{
							"key": "role",
							"value": "owner",
							"type": "text"
						}
					]
				},
				"url": {
					"raw": "{{apiUrl}}/api/v1/auth/register",
					"host": [
						"{{apiUrl}}"
					],
					"path": [
						"api",
						"v1",
						"auth",
						"register"
					]
				}
			},
			"response": []
		},
		{
			"name": "Change Password",
			"event": [
				{
					"listen": "test",
					"script": {
						"exec": [
							"let json = pm.response.json();\r",
							"\r",
							"if (json.token) {\r",
							"    pm.environment.set(\"token\", json.token);\r",
							"}"
						],
						"type": "text/javascript",
						"packages": {},
						"requests": {}
					}
				}
			],
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "{{token}}",
							"type": "string"
						}
					]
				},
				"method": "POST",
				"header": [],
				"body": {
					"mode": "formdata",
					"formdata": [
						{
							"key": "current_password",
							"value": "123456",
							"type": "text"
						},
						{
							"key": "new_password",
							"value": "12345678",
							"type": "text"
						},
						{
							"key": "new_password_confirmation",
							"value": "12345678",
							"type": "text"
						}
					]
				},
				"url": {
					"raw": "{{apiUrl}}/api/v1/users/change-password",
					"host": [
						"{{apiUrl}}"
					],
					"path": [
						"api",
						"v1",
						"users",
						"change-password"
					]
				}
			},
			"response": []
		},
		{
			"name": "Logout",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "{{token}}",
							"type": "string"
						}
					]
				},
				"method": "POST",
				"header": [],
				"url": {
					"raw": "{{apiUrl}}/api/v1/auth/logout",
					"host": [
						"{{apiUrl}}"
					],
					"path": [
						"api",
						"v1",
						"auth",
						"logout"
					]
				}
			},
			"response": []
		},
		{
			"name": "Refresh Token",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "{{token}}",
							"type": "string"
						}
					]
				},
				"method": "POST",
				"header": [],
				"url": {
					"raw": "{{apiUrl}}/api/v1/auth/refresh",
					"host": [
						"{{apiUrl}}"
					],
					"path": [
						"api",
						"v1",
						"auth",
						"refresh"
					]
				}
			},
			"response": []
		},
		{
			"name": "Dashboard",
			"request": {
				"auth": {
					"type": "bearer",
					"bearer": [
						{
							"key": "token",
							"value": "{{token}}",
							"type": "string"
						}
					]
				},
				"method": "GET",
				"header": [],
				"url": {
					"raw": "{{apiUrl}}/api/v1/dashboard/1",
					"host": [
						"{{apiUrl}}"
					],
					"path": [
						"api",
						"v1",
						"dashboard"
					]
				}
			},
			"response": []
		}
	],
	"event": [
		{
			"listen": "prerequest",
			"script": {
				"type": "text/javascript",
				"packages": {},
				"requests": {},
				"exec": [
					""
				]
			}
		},
		{
			"listen": "test",
			"script": {
				"type": "text/javascript",
				"packages": {},
				"requests": {},
				"exec": [
					""
				]
			}
		}
	]
}