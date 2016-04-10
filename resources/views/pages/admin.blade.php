@extends('layouts.master')

@section('title')
Admin picoCMS
@stop
@section('scripts')
<script src="../js/script.js"></script>
@stop

@section('body')


<h1>Admin Section</h1>
{!!$page_content !!}
<!-- template for the modal component -->


<h3>Current User menu</h3><hr>
  <div id="app">
 
  <article v-for="item in messages">
      
	         <button style="position:relative; left:0px; display: block;" type="button" class="btn btn-danger btn-xs" id="remove" v-on:click="removes($index, item.id)"><span class="glyphicon glyphicon-remove"></span></button></span></button>
	         
	         <button style="position:relative; display: block;"id='menu-@{{$index}}' type="button" class="btn btn-info" id="show-modal" v-on:click="clicked($index, item.id)">@{{item.title}}</button>

	         

	         <button v-if="item.issection==1" style="position:relative; float: right; display: block;" type="button" class="btn btn-warning btn-xs" id="add" v-on:click="addsubpage($index)"><span class="glyphicon glyphicon-plus-sign"></span></button></span></button>

         
  </article><span><button style="position:relative; top:2em;" type="button" class="btn btn-default btn-sm" v-on:click="onClickAddPage">
          <span class="glyphicon glyphicon-plus"></span></button></span>
<hr><br><hr>
<modal :show.sync="showModal" :newpage.sync="test">
</modal>



  
 
</div>
<!-- template for the modal component -->
<script type="x/template" id="modal-template">

  <div class="modal-mask" v-show="show" transition="modal">
    <div class="modal-wrapper">
      <div class="modal-container">

        <div class="modal-header">
          <slot name="header">
            Name of the page:
          </slot>
        </div>

        <div class="modal-body">
          <slot name="body">
            
			<div class="form-group">
  				<label for="usr">Name:</label>
  				<input id="usr" class="form-control" v-model="newpage.title" @keyup.enter="entered(newpage.title, newpage.issection)">
  			</div>
          
            <div class="radio">
 			 <label><input type="radio" v-model="newpage.issection" value="0" :checked="newpage.issection==0">Root page</label>
			</div>
			<div class="radio">
 			 <label><input type="radio" v-model="newpage.issection" value="1" :checked="newpage.issection==1">Section</label>
			</div>
      <label><input type="checkbox" v-model="newpage.ispublished" :checked="newpage.ispublished==1"> Published</label>
            
            
           
          </slot>
        </div>

        <div class="modal-footer">
          <slot name="footer">
            <button class="modal-default-button"
              @click="discharge()">
              Cancel
            </button>
            <button class="modal-default-button"
              @click="entered(newpage.title, newpage.issection, newpage.ispublished)">
              OK
            </button>
          </slot>
        </div>
      </div>
    </div>
  </div>

</script>

@stop