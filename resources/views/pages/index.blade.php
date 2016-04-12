@extends('layouts.master')

@section('title')
{{$current_page->title}}
@stop
@section('scripts')
<script src="../js/script2.js"></script>
<style type="text/css">
	.toggle {
	text-align: center;
	width: auto;
	/* auto, since non-WebKit browsers doesn't support input styling */
	height: auto;
	
	top: 0;
	bottom: 0;
	margin: auto 0;
	border: none; /* Mobile Safari */
	-webkit-appearance: none;
	appearance: none;
}

</style>
@stop
@section('body')
<h1>Hello, Customer!</h1>
<h3 style="color:red;"> To update record use "Tab" or "Enter" key</h3>

<div v-show="<?php if ($current_page->title!='home'){echo 'false';} else {echo 'true';}?>" id = "app">

           
<div><label>Search:</label><input style="width:200px;display:inline-block;" class="form-control" v-model="sname" placeholder="enter your searchquery">
<label>Records:</label>
<button style="position:relative; display:inline-block;" type="button" class="btn btn-info" @click="addNewRecord()">@{{addButton}}</button><button style="position:relative; display:inline-block;" type="button" class="btn btn-danger" @click="deleteRecord()">@{{delButton}} </button><a href="#" @click="uncoverCreated()">Created <span class= "badge" v-bind:class="{'progress-bar-danger': bedgeCreated}">@{{isCreated}}</span></a>
	<a href="#"  @click="uncoverUpdate()">Updated <span class= "badge" v-bind:class="{'progress-bar-danger': bedgeEdite}">@{{isUpdated}}</span></a>
	<a href="#" @click="uncoverDeleted()">Deleted <span class="badge"v-bind:class="{'progress-bar-danger': badgeDelete}">@{{isDeleted}}</span></a>
</div>
<table class="table table-hover table-responsive">
  <thead>
  <tr>
  <th v-if="delCheckBox">Select to Delete</th>
    <th>Title</th>
    <th>Firstname</th>
    <th>Lastname<span @click="order = order * -1"><span v-if="order<0" class="glyphicon glyphicon-arrow-up"></span><span v-if="order>0" class="glyphicon glyphicon-arrow-down"></span></span></th> 
    <th>Age</th>
    <th>Phone</th>
    <th>Email</th>
  </tr>
  </thead>
    <tbody>
   
 <tr v-if="shown">
 
 <td v-if="shown"><input v-if="shown"type="text" v-model="newRecord.sex" v-on:keydown.tab="checkNewRecord()"></td>
  <td v-if="shown"><input v-if="shown"type="text" v-model="newRecord.firstName" v-on:keydown.tab="checkNewRecord()"></td>
  <td v-if="shown"><input v-if="shown"type="text" v-model="newRecord.lastName" v-on:keydown.tab="checkNewRecord()"></td>
  <td v-if="shown"><input v-if="shown"type="text" v-model="newRecord.age" v-on:keydown.tab="checkNewRecord()"></td>
  <td v-if="shown"><input v-if="shown"type="text" v-model="newRecord.phone" v-on:keydown.tab="checkNewRecord()"></td>
  <td v-if="shown"><input v-if="shown"type="text" v-model="newRecord.email" v-on:keydown.tab="checkNewRecord()"></td>
 </tr>
  <tr v-for="record in records | filterBy sname ">

  <td v-if="delCheckBox"><input v-if="delCheckBox" type="checkbox" id="@{{record.id}}" value="@{{record.id}}" v-model="deleteRecords"></td>



    <td><input class="toggle" type="text" 
    v-on:keydown.tab="fieldupdate($index, record.id, record.sex, 'sex')" 
    v-on:keyup.enter="fieldupdate($index, record.id, record.sex, 'sex')"
    v-model="record.sex" value="@{{record.sex}}"></td>

    <td><input class="toggle" type="text" 
    v-on:keyup.enter="fieldupdate($index, record.id, record.firstName, 'firstName')"
    v-on:keydown.tab="fieldupdate($index, record.id, record.firstName, 'firstName')" v-model="record.firstName" value="@{{record.firstName}}" ></td>
    
    <td><input class="toggle" type="text" 
   v-on:click="orderBy=false" 
   v-on:keyup.enter="fieldupdate($index, record.id, record.lastName, 'lastName')"
   v-on:keydown.tab="fieldupdate($index, record.id, record.lastName, 'lastName')" 
   v-model="record.lastName" value="@{{record.lastName }}" ></td>

    <td>
    <input class="toggle" type="text" 
    v-on:keyup.enter="fieldupdate($index, record.id, record.age, 'age')" 
    v-on:keydown.tab="fieldupdate($index, record.id, record.age, 'age')" 
    v-model="record.age" value="@{{record.age }}"></td> 
    <td>
    <input class="toggle" type="text" 
    v-on:keyup.enter="fieldupdate($index, record.id, record.phone, 'phone')" 
    v-on:keydown.tab="fieldupdate($index, record.id, record.phone, 'phone')" 
    v-model="record.phone" value="@{{record.phone }}"></td>
    <td>
    <input class="toggle" type="text" 
    v-on:keyup.enter="fieldupdate($index, record.id, record.email, 'email')" 
    v-on:keydown.tab="fieldupdate($index, record.id, record.email, 'email')" 
    v-model="record.email" value="@{{record.email }}"></td>
  </tr>

   </tbody>
    </table>
<label>Showing @{{from}} to @{{to}} of @{{total}} entries</label>
<div><ul class="pagination pagination-sm">
  <li class="static" v-bind:class="{'active': count==current_page}" v-for="count in counts"><a @click="nextrecoreds(count)">@{{count}}</a></li>
 </ul></div>



 </div>

@stop