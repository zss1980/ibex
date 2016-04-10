<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Event;

use App\Events\PageRouteCreated;
use App\Events\PageRouteDeleted;
use App\Events\PageRouteUpdated;
use App\Events\CitizenRecordUpdate;
use App\Events\CitizenRecordCreate;
use App\Events\CitizenRecordDeleted;

use App\Http\Requests;
use App\Page;
use App\Content;
use App\Citizen;

class PageController extends Controller
{
    //Set a home page variable
    //
    //@return a redirect to routing method with parametr of home page
    public function index()
    {
        
        
        $page='Home';
        
        return redirect()->action('PageController@pages', $page);
       
    }

    //Get a view of requested page with a dynamic menu and content or throw a 404-error page
    //
    //@param string $page page from the route.php
    //@return view with parameters string $current_page, string $content, array $menu 
    public function pages($page)
    {
        

        $menu = $this->buildmenu();//building a dynamic menu
        

       if(Page::where('title', $page)->first())
        {
            $current_page = Page::where('title', $page)->first();
            $content = 'there is nothing yet';
            /*if ($current_page->id===247) {
                $contents = Citizen::paginate(25);
            }*/
           

        return view('pages.index')->with('current_page', $current_page)
                                    ->with('contents', $content)
                                    ->with('pages', $menu);

        } else 
        {
            return view('errors.404')->with('pages', $menu);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminindex() {
        $current_page = 'Admin';
        $content = "<div class='alert alert-success'>The content of Admin Section</div>";
        
        
        $menu = $this->buildmenu();//building a dynamic menu
        return view('pages.admin')->with('current_page', $current_page)
                                    ->with('page_content', $content)
                                    ->with('pages', $menu);
    }

 public function adminget() {
        
        return Page::all();
    }

     public function getrecords($num) {
        $data=array();
        $data[0]= Citizen::take(10)->get();
        $data[1]= Citizen::count();
        $data[2]= Citizen::paginate();

        return Citizen::paginate();
    }

    public function admingetpage($id) {
        
        return Page::find($id);
    }
 
    public function adminset(Request $request) {
        //is Page
        if ($request->ajax())
        {
            if (Page::where('route_name', $request->title)->first())
        
        {
            $pageupdate = Page::where('route_name', $request->title)->first();
            $pageupdate->route_name = "/" . $request->title;
            $pageupdate->title = $request->title;
            $pageupdate->issection = $request->issection;
            $pageupdate->ispublished = $request->ispublished;


        } else {
            
            $newpage = new Page;
            $newpage->route_name = "/" . $request->title;
            $newpage->title = $request->title;
            $newpage->issection = $request->issection;
            $newpage->ispublished = $request->ispublished;
            //$newpage->save();

            Event::fire(new PageRouteCreated());
            return "dsf";

        }
        
    }
}

public function adminseti(Request $request) {
        //is Page
        
            $newpage = new Page;
            $newpage->route_name = "/" . $request->title;
            $newpage->title = $request->title;
            $newpage->issection = $request->issection;
            $newpage->ispublished = $request->ispublished;
            $pageToCreate = $newpage;
            if ($newpage->save()) {

            Event::fire(new PageRouteCreated($pageToCreate));
        }

            //return $newpage;

        }
        
  

    //Get a collection of pages as objects
    //
    // @return an array of App\Page - an Eloquant model, with a subarray of subpages as well    

    public function buildmenu()
    {
        $pages = Page::where('ispublished', 1)->where('ischild', '0')->get();
        foreach ($pages as $page) 
        {
            if ($page->issection!=0)//checking if an object has subpages
            {
                if (Page::where('parent_id', $page->id)->get())
                {
                    $children = Page::where('parent_id', $page->id)->get();
                }
                $count=0;
                foreach ($children as $child)//building array of subpages
                {
                    if (Page::find($child->id))
                    {
                        if (Page::find($child->id)->published!=1)
                        {
                            unset($children[$count]);

                        }
                    }
                    $count++;
                }
                $page->children = $children;
                }
            }
        return $pages;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Page::destroy($request->id)){
            
            Event::fire(new PageRouteDeleted($request->index));
        }

    }

    public function destroyItem(Request $request)
    {
        if ($request->id!=247){
        if (Page::destroy($request->id)){
            
            Event::fire(new PageRouteDeleted($request->index));
        }
    }

    }

    public function editItem(Request $request)
    {
        if (Page::find($request->itemId))
        
        {
            $pageupdate = Page::find($request->itemId);
            $pageupdate->route_name = "/" . $request->title;
            $pageupdate->title = $request->title;
            $pageupdate->issection = $request->issection;
            $pagetoBeSent = $pageupdate;
            $pageupdate->ispublished = $request->ispublished;
//index:this.edited, itemId: itemedited, title: title, issection: issection
             if ($pageupdate->save()){
            
                Event::fire(new PageRouteUpdated($request->index, $pagetoBeSent));
            }

        }
    }
    public function editRecord(Request $request)
    {
        if (Citizen::find($request->itemId))
        
        {
            $recordupdate = Citizen::find($request->itemId);
            if ($request->field == 'lastName'){
            $recordupdate->lastName = $request->newData;    
            } elseif ($request->field == 'firstName'){
            $recordupdate->firstName = $request->newData;    
            } elseif ($request->field == 'phone'){
            $recordupdate->phone = $request->newData;    
            }elseif ($request->field == 'sex'){
            $recordupdate->sex = $request->newData;    
            }elseif ($request->field == 'age'){
            $recordupdate->age = $request->newData;    
            }elseif ($request->field == 'email'){
            $recordupdate->email = $request->newData;    
            }

            $recordtoBeSent = $recordupdate;
            //$recordupdate->$request->field = $request->newData;
            if ($recordupdate->save()){
            
                Event::fire(new CitizenRecordUpdate($request->index, $recordtoBeSent));
            }

        }
    }

    public function createRecord(Request $request)
    {
        
            $newrecord = new Citizen;
            $newrecord->lastName = $request->lastName;
            $newrecord->firstName = $request->firstName;
            $newrecord->sex = $request->sex;
            $newrecord->age = $request->age;
            $newrecord->phone = $request->phone;
            $newrecord->email = $request->email; 


            $recordtoBeSent = $newrecord;
            //$recordupdate->$request->field = $request->newData;
            if ($newrecord->save()){
            
                Event::fire(new CitizenRecordCreate($recordtoBeSent));
            }

        
    }


     public function deleteRecord(Request $request)
    {
        //$deletedRecords = array();
        //$counti=0;
        for ($i = 0; $i<count($request->delArray);$i++){
            $recordToDelete = Citizen::find($request->delArray[$i]);
            if (Citizen::destroy($request->delArray[$i])){
                Event::fire(new CitizenRecordDeleted($recordToDelete));
            }

        }

        /*$deleted = $counti + 1;

        if ($counti>0) {
            Event::fire(new CitizenRecordDeleted($deleted, $deletedRecords));
        }
*/
    } 
    
    
}
