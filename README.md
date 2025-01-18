# Ore Laravel Package 

## About Ore API Support Package

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
- Very simple to install it using the composser
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
 

