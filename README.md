# RESTFUL API TASK

This contains the application code for the RESTFUL API TASK. The app is build on top of [Laravel framework](http://laravel.com/docs) which runs on the XAMP OR LAMP stack.


## Requirements
* PHP Version >= 7.3
* composer 
* for more detail you can visit this link [Laravel Installation Guide](https://medium.com/@owthub/laravel-8-installation-guide-php-framework-de42e145765c)


## Setting up

Follow these steps to set up the project.

```
git clone <project.url> <project>
cd <project>
composer install
change the  .env.example to .env and Add databse name in db DB_DATABASE  and DB_USERNAME And DB_PASSWORD
```

Change the values of the `.env` file as necessary.

## Run the following Command

* php artisan migrate
* php artisan passport:install
* php artisan key:generate

This is the guide for API usage.

1-	User Registration
User should be register by API path and JSON data set given below, 
•	Role 1 is assigned to support team members
•	Role 2 is assigned to customers 
Api path =  http://localhost/dream_api_task/public/api/register

This will give “Bearer Token” which should be used accordingly
Inside path should also be used according to folder name

Method: Post
{
	“name”:’ “Anas”
	"role":"1",
	"email":"rajaanassss786@mailinator.com",
	"password":"123"
}


2-	See all the register users 
Path: http://localhost/dream_api_task /public/api/show_user
Method: Get


3-	Send new message
Api path =  http://localhost/dream_api_task/public/api/register
Method: Post
Data=
{
 "customer_user_id" : "1",
   "status":"0",
   "message":"this is my second message”}



4-	See  message details
Path  http://localhost/ dream_api_task/public/api/customer_questions/id
Method:Get
Customer user id should be used here


5-	Support team answer
Path http://localhost/dream_api_task/public/api/support_team_message
Method: post
Data =
 {
 "customer_user_id" : "1",
   "chat_id":"1",
   "message":"this is my send reply",
   "support_user_id":"1" ,
   "msg_status":"1" 
 }


6-	Get all the questions


Path http://localhost/dream_api_task/public/api/show_questions
Method: Get

7-	Search question by name or status
Path : http://localhost/dream_api_task/public/api/search_questions 
Method: Post
Data {
	 "name" : "Khan",
	 "status":1
 }


8-	Mark as spam
Path :  http://localhost/dream_api_task/public/api/mark_as_spam
Method: Post
Data {
	 "id":1,
	 "status":3
 }





## Testing

* After all this thing done your URL is  [task APi](http://localhost/task_api/public/)

