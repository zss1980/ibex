Vue.component('modal', {
  template: '#modal-template',
  props: {
    show: {
      type: Boolean,
      required: true,
      twoWay: true    
    }, 
    newpage: {
      type: Object,
      required: true,
      twoWay: true    
    }
  },
  methods: {
    entered: function(title, issection, ispublished)
    {
      
      this.$dispatch('modal-msg', title, issection, ispublished);
      this.show=false;
    },
    discharge: function() {
      this.$dispatch('discharge');
      
      this.show=false;

    }
  }
})


window.addEventListener('load', function () {
    // register modal component


    var vm = new Vue({
  el: '#app',
  data: {
  	messages:[],
    message:[],
  	submitted: false,
  	newMessage: {
  		title: '',
  		issection: '',
      ispublished:''
  	},
    toDelete: false,
    edited: -1,
    itemedited: -1,
    showModal: false,
    oldmessage:-1,
    cashedoldmessage:{
      title:'',
      issection:'',
      ispublished:''

    },
    test: {},
    removemess:{}, 
    postmessage: {
      title:'',
      issection:'',
      ispublished:''
    },
    

  },
 
  computed: {
  	errorsa: function() {
  		for (var key in this.newMessage) {
  			if (! this.newMessage[key]) return true;
  		}

  		return false;
  	}
  },
  
  ready: function(){
  	this.fetchMessages();
    this.websocketOn();
  },

  events: {
    'modal-msg': function(title, issection, ispublished){
    if (this.edited!=-1){
      var editedItemMes = {index:this.edited, itemId: this.itemedited, title: title, issection: issection, ispublished: ispublished};
      this.editOnServer(editedItemMes);
      //this.edited=-1;
      //this.messages[this.edited].title=title;
      //this.messages[this.edited].issection=issection;
      //his.postmessage=this.messages[this.edited];
    } else {
    var newindex = this.messages.length;
    //this.messages.push({title: title, issection: issection});
    //this.postmessage = this.messages[newindex];
    this.postmessage = {title: title, issection: issection, ispublished: ispublished};
    this.sendserver (this.postmessage); 
  }
    

    },

    'discharge': function(){
      if (this.oldmessage==0){
      this.messages.$remove(this.messages[this.edited]);
    } else {
      this.messages.$set(this.edited, {title: this.cashedoldmessage.title, 
        issection:this.cashedoldmessage.issection});
    }
  },

  'ed-mes': function(data){
    this.messages[data.index].title=data.title;
    this.messages[data.index].issection=data.issection;
    this.messages[data.index].ispublished=data.ispublished;
    this.edited=-1;
  },

  'nw-mes': function(id){
    this.fetchMessage(id);
    

  },

  'del-mes': function(indxtoDel){
    this.removemess = this.messages[indxtoDel];
        this.messages.$remove(this.removemess);
        
  },

  'push-nw-mes': function(){
    this.addPageRoute(this.message.title, this.message.issection, this.message.ispublished, this.message.id);
  }

},

  methods: {
  	fetchMessages: function () {
  		this.$http.get('/admin/adminget').then(function(response)
  	{
  		this.$set('messages', response.data);
  	});

  	},

    fetchMessage: function (id) {
      this.$http.get('/admin/admingetpage/'+id).then(function(response)
    {
      this.$set('message', response.data);
      vm.$emit('push-nw-mes');
    });

    },

    addPageRoute: function (title, issection, id, ispublished) {
      this.messages.push({title: title, issection: issection, ispublished: ispublished, id: id});
    },

    onClickAddPage: function(){
      var currentindx = this.messages.length;
      //this.messages.push({ title: 'New page', issection: '0'});
      this.test={ title: 'New page', issection: '', ispublished:''};
      //this.edited = currentindx;
      this.oldmessage=0;

      this.showModal = true;

    },

    websocketOn: function(){
      var pusher = new Pusher('e480295dc936212f4491', {
      encrypted: false});
      var channel = pusher.subscribe('pageRouteAction');
      channel.bind('App\\Events\\PageRouteCreated', function(page) {
      vm.$emit('nw-mes', page.id);
});
      channel.bind('App\\Events\\PageRouteDeleted', function(data) {
      vm.$emit('del-mes', data.isdeleted);
});
      channel.bind('App\\Events\\PageRouteUpdated', function(data) {
      vm.$emit('ed-mes', data);
});
    },

  	//onSubmitForm: function () {
  	//var postmessage = this.newMessage;

  	//this.messages.push(postmessage);
  	//this.newMessage = { title: '', parent_id: ''};
  	//this.$http.post('/send', postmessage);



  	//this.submitted = true;
  	//},

    sendserver: function(postmessage){
     // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
      var sendStatus;
      sendStatus = this.$http.post('/admin/adminsent', postmessage).then(function(response)
        {
          //alert('updated');
        }, function(response){
          console.log(response.data);

        });
    },

     sendDelete: function(delitem){
     // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
      var sendStatus;
      sendStatus = this.$http.post('/admin/deleti', delitem).then(function(response)
        {
          //alert('updated');
        }, function(response){
          console.log(response.data);

        });
    },

    editOnServer: function(editedItemMes){
     // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
      var sendStatus;
      sendStatus = this.$http.post('/admin/editi', editedItemMes).then(function(response)
        {
          //alert('updated');
        }, function(response){
          console.log(response.data);

        });
    },

    fieldupdate: function(data){
      alert(data.attribute);
    },

    clicked: function (indx, itemId) {
    this.edited = indx;
    this.itemedited = itemId;
    this.oldmessage=1;  
    this.test= this.messages[indx];
    this.cashedoldmessage.title=this.messages[indx].title;
     this.cashedoldmessage.issection=this.messages[indx].issection;
     this.cashedoldmessage.ispublished=this.messages[indx].ispublished;
    this.showModal=true;
    },

    removes: function(indx, itemId){
      var delitem = {id: itemId, index: indx};
      //this.removemess = this.messages[indx];
      vm.sendDelete(delitem);

    

    },  

     entered: function (info) {
      this.showModal=false;
    this.item.title = info;
    }


  }

});
})

