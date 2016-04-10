window.addEventListener('load', function () {
    // register modal component


    var vm = new Vue({
  el: '#app',
  data: {
  	records:[],
  	unseenRecords:[],
    unseenCreatedRecords: [],
  	total: 0,
   	per_page: 0,
    current_page: 0,
    last_page: -1,
    next_page_url: '',
    prev_page_url: '',
    from: 0,
    to: 0,
    recordsToEdit: -1,
    isUpdated:0,
    addButton: 'Add new',
    shown: false,
    newRecord: {
      'firstName': '',
        'lastName' : '',
        'sex': '',
        'age': '',
        'phone': '',
        'email': ''

    },
    isCreated:0,
    authori: false,
    bedgeCreated: false,
    bedgeEdite: false,

    delButton: 'Delete',
    isDeleted: 0,
    badgeDelete: false,
    delCheckBox:false,
    deleteRecords: [],
    unseenDelRecords: [],

    counts:[],
  	order: 1,
  	isOrder: false,
  	sortKey: 'lastName',
  	sname: '',
  },

ready: function(){
  	this.fetchRecords();
  	this.websocketOn();
  	
  			this.setCounts();
  		
    //this.websocketOn();
  },

events: {
    'new-record': function(data){
    	var indexId = this.checkRecordId(data.id);

   if (indexId!=-1) {
	   this.records[indexId].firstName = data.firstName;
	   this.records[indexId].lastName = data.lastName;
	   this.records[indexId].phone = data.phone;
	   this.records[indexId].age = data.age;
	   this.records[indexId].sex = data.sex;
	   this.records[indexId].email = data.email;
	   this.records[indexId].created_at = data.created_at;
	   this.records[indexId].updated_at = data.updated_at;
	   this.recordsToEdit=-1;
	} else {
		this.bedgeEdite = true;
		this.isUpdated++;
		this.unseenRecords.push(
{
		'id': data.id,
        'firstName': data.firstName,
        'lastName' : data.lastName,
        'sex': data.sex,
        'age': data.age,
        'phone': data.phone,
        'email': data.email
}
			);


	}

  },

  'added-record': function(data){
    if (!this.authori){
    this.bedgeCreated = true;
    this.isCreated++;
    this.unseenCreatedRecords.push(
    {
        'id': data.id,
        'firstName': data.firstName,
        'lastName' : data.lastName,
        'sex': data.sex,
        'age': data.age,
        'phone': data.phone,
        'email': data.email
    }
      );
  } else {
    this.records=[];
    this.records.push(
    {
        'id': data.id,
        'firstName': data.firstName,
        'lastName' : data.lastName,
        'sex': data.sex,
        'age': data.age,
        'phone': data.phone,
        'email': data.email
    }
      );
  }


  },

  'deleted-record': function(data){
    var indexId = this.checkRecordId(data.id);

   if (indexId!=-1) {
    this.records.$remove(this.records[indexId]);

   }else {
    this.badgeDelete = true;
    this.isDeleted++;
    this.unseenDelRecords.push(
    {
        'id': data.id,
        'firstName': data.firstName,
        'lastName' : data.lastName,
        'sex': data.sex,
        'age': data.age,
        'phone': data.phone,
        'email': data.email
    }
      );
  }
    

    }
  },

  methods: {

  	deleteRecord: function(){

      if (this.delCheckBox) {
      
      alert('to be Deleted' +this.deleteRecords);
      var delMess = {delArray: this.deleteRecords}
      this.deleteFromServer(delMess);

      this.delCheckBox= false;
      this.deleteRecords=[];
    }else {
      this.delCheckBox= true;
      this.shown=false;
      this.addButton = "Add new";
    }

    },

    checkNewRecord: function(){
      if (this.newRecord.sex!='' && 
        this.newRecord.firstName!='' &&
        this.newRecord.lastName!='' &&
        this.newRecord.age!='' &&
        this.newRecord.phone!='' &&
        this.newRecord.email!='')
      {
          this.addButton = "Submit";
        } else {
          this.addButton = "Cancel";
        }

    },

    addNewRecord: function(){
      this.delCheckBox= false;
      this.deleteRecords=[];
      if (this.newRecord.sex!='' && 
        this.newRecord.firstName!='' &&
        this.newRecord.lastName!='' &&
        this.newRecord.age!='' &&
        this.newRecord.phone!='' &&
        this.newRecord.email!='')
      {
          
         // alert('done');
          var recordToServer = {
          firstName: this.newRecord.firstName, 
           lastName: this.newRecord.lastName,
           sex: this.newRecord.sex,
           phone: this.newRecord.phone,
           age: this.newRecord.age,
           email: this.newRecord.email};

          this.shown=false;
          this.addButton = "Add new";
          this.newRecord=[];
          this.authori = true;

          this.sendNewRecord(recordToServer);

      } else {
      if (this.shown==false) {
        this.shown=true;
        this.addButton = "Cancel";
      } else {
        this.shown=false;
        this.addButton = "Add new";
      }
    }
      //alert(this.newRecord.sex);
      

      /*this.records.push(
{
    'id': -1,
        'firstName': 'enter your data',
        'lastName' : 'enter your data',
        'sex': 'enter your data',
        'age': 'enter your data',
        'phone': 'enter your data',
        'email': 'enter your data'
}
        );*/

    },

    checkRecordId: function(id){
  		for (i=0;i<this.records.length;i++){
  			if (this.records[i].id===id){
  				return i;
  			}
  		}
  		return -1;
  	},

  	fieldupdate: function(index, id, data, key){
      //alert(index + ' ' + id + ' ' + data + ' ' + key);
      this.isOrder=true;
      this.recordsToEdit = id;
      var editedItemMes = {index:index, itemId: id, newData: data, field: key};
      this.editOnServer(editedItemMes);


    },

    uncoverUpdate: function(){
       this.delCheckBox= false;
       this.shown=false;
       this.addButton = "Add new";
       this.deleteRecords=[];

    	this.$set('records', this.unseenRecords);
    	this.bedgeEdite = false;
		this.isUpdated = 0;
		this.unseenRecords=[];

    },

    uncoverDeleted: function(){
       this.delCheckBox= false;
       this.shown=false;
       this.addButton = "Add new";
       this.deleteRecords=[];

      this.$set('records', this.unseenDelRecords);
      this.badgeDeleted = false;
    this.isDeleted = 0;
    this.unseenDelRecords=[];

    },

    uncoverCreated: function(){
       this.delCheckBox= false;
       this.shown=false;
       this.addButton = "Add new";
       this.deleteRecords=[];

      this.$set('records', this.unseenCreatedRecords);
      this.bedgeCreated = false;
    this.isCreated = 0;
    this.unseenCreatedRecords=[];

    },

    fetchRecords: function(){
    	this.$http.get('/data/records/'+'pages').then(function(response)
  	{
  		this.$set('records', response.data.data);
  		this.$set('total', response.data.total);
  		this.$set('per_page', response.data.per_page);
  		this.$set('current_page', response.data.current_page);
  		this.$set('last_page', response.data.last_page);
  		this.$set('next_page_url', response.data.next_page_url);
  		this.$set('prev_page_url', response.data.prev_page_url);
  		this.$set('from', response.data.from);
  		this.$set('to', response.data.to);

  		for (i=0;i<this.last_page;i++){
  			this.counts.push(i+1);
  		}


  	});

  	},

  	nextrecoreds: function(pnum){
      this.delCheckBox= false;
       this.shown=false;
       this.addButton = "Add new";
       this.deleteRecords=[];

  		this.$http.get('/data/records/'+'pages'+'?page='+pnum).then(function(response)
  	{
  		this.$set('records', response.data.data);
  		this.$set('total', response.data.total);
  		this.$set('per_page', response.data.per_page);
  		this.$set('current_page', response.data.current_page);
  		this.$set('last_page', response.data.last_page);
  		this.$set('next_page_url', response.data.next_page_url);
  		this.$set('prev_page_url', response.data.prev_page_url);
  		this.$set('from', response.data.from);
  		this.$set('to', response.data.to);
  	});

  	},
  	setCounts: function(){
  		for (i=0;i<this.last_page;i++){
  			this.counts.push(i+1);
  		}

  	},
  	editOnServer: function(editedItemMes){
     // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
      var sendStatus;
      sendStatus = this.$http.post('/data/records/editi', editedItemMes).then(function(response)
        {
          //alert('updated');
        }, function(response){
          console.log(response.data);

        });
    },

    sendNewRecord: function(newRecordMes){
     // Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
      var sendStatus;
      sendStatus = this.$http.post('/data/records/create', newRecordMes).then(function(response)
        {
          //alert('updated');
        }, function(response){
          console.log(response.data);

        });
    },

    deleteFromServer: function(delRecords){
      sendStatus = this.$http.post('/data/records/delete', delRecords).then(function(response)
        {
          //alert('updated');
        }, function(response){
          console.log(response.data);

        });

    },



    websocketOn: function(){
      var pusher = new Pusher('e480295dc936212f4491', {
      encrypted: false});
      var channel = pusher.subscribe('RecordChanges');
      channel.bind('App\\Events\\CitizenRecordUpdate', function(record) {
      vm.$emit('new-record', record);
});
      channel.bind('App\\Events\\CitizenRecordCreate', function(record) {
      vm.$emit('added-record', record);
});
       channel.bind('App\\Events\\CitizenRecordDeleted', function(record) {
      vm.$emit('deleted-record', record);
});
      
    },

	},
  

})
})