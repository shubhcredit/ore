# Laravel Ore Package 

## About Ore Package

This is the package which help to fast API development.

### How it support ?

- By use it less code 
- It is very easy to use
- Only define the model file
- Get resource task automated like
- List, save, show, edit, update and delete

### When we use it ?

- Best use of the Ore is automate the crud of your module
- For example : some module only want crud on that
- Then use it like , category, subcategory, country, city, state, class, department etc  

### How to install it ?

- Very simple to install it using the composer
```
    composer require shubhcredit/ore
```
- After intall verify by hit the api route as 

```
 {domain}/api/ore/up
```
- Response will as 

```
    {
        "Status": true,
        "message": "Ore api route is ready to serve | Make development easy..."
    }
```

### How to use it ?

 - Create migration and model 
 - Then add your Ore property in your model file as 

 ```
   public $ore=[
        ['name'=>'name','component'=>'text','required'=>'*','orevl'=>"required|unique:categories,name"],
        ['name'=>'image','component'=>'file','required'=>'*','orevl'=>'mimes:png,jpg,jpeg'],
        ['name'=>'group','component'=>'option','required'=>'*','orevl'=>'required','relation'=>"Group,id,name"],
        ['name'=>'department','component'=>'option','required'=>'*','orevl'=>'required','relation'=>"Department,id,name"],
        ['name'=>'description','component'=>'textarea','required'=>'*','orevl'=>'required'],
        ['name'=>'status','component'=>'enum','required'=>'*','orevl'=>'required','enum'=>['0'=>'Deactive','1'=>'Active']],
    ];
 ```
 - Explanation :    
 - 'name' column name
 - 'component' which type of data will be store
 - 'required' use to show on input field label
 - 'orevl' define the laravel input validation
 - 'enum' add enum value , it save key in db it show mapped value 

 - In above example we take the category module 
 - Which has relation with the group and department module
 - Define the relation as 
 ```
   public function group_is(){
        return $this->belongsTo(Group::class,'group');
    }

    public function department_is(){
        return $this->belongsTo(Department::class,'department');
    }

 ```
 - Now define the your module route as
 - Use the controller in your route file

 ```
    use Shubhcredit\Ore\Controllers\Api\OreController;
 ``` 
 - Define resource reoute prifix with 'ore'
 ```
    Route::resource('/ore/category', OreController::class);
 ```

### Note : 
- ore prefix is mendetory in the route

## How to use permission ?
Very simple to use the permission in the ore package 

### Ore gave these permission as 

```
 "ore_action": {
        "view": true,
        "create": true,
        "save": true,
        "edit": true,
        "update": true,
        "delete": false
    }
```
This key will available in each API response which is use on frontend creation 

### Use of this permission 
- __*view*__  denote to view 
- __*create*__ denote to get meta info to make the create form
- __*save*__ denote to save the form data
- __*edit*__ denote to get meta info and data to make edit form
- __*update*__ denote to update form data
- __*delete*__ denote to delete the data 

### Define the permission 
To define the permission make the method in the model file ore will automatically fetch, follow these step to define the method :-

1. Use 'oreCan' prifix in the method name of permission 
    for example view permission then method name will be as 'oreCanView'.
2. Function have not parameter
3. Function return bool i.e. ture/false

Take an example as
```
    public function oreCanView(){
        //@MAKE::you code here
        return true;
    }
```
__Note ::__ Defult value of the all permission is ture until user defined method overwrite it. 
