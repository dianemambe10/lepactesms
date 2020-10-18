@extends('layouts.app')



@section('link')


    <style type="text/css" xmlns:v-on="http://www.w3.org/1999/xhtml" xmlns:v-on="http://www.w3.org/1999/xhtml">
        #loading-indicator {
            position: absolute;
            left: 10px;
            top: 10px;
        }

        /* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */
        .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url( {{asset('img/loading.gif')}})
            50% 50%
            no-repeat;
        }

        /* When the body has the loading class, we turn
           the scrollbar off with overflow:hidden */
        body.loading .modal {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .modal {
            display: block;
        }

        .modal-mask {
            position: fixed;
            z-index: 9998;
            top: 0;
            left: 0;
            width: 100%;
            height: 90%;
            background-color: rgba(0, 0, 0, .5);
            display: table;
            transition: opacity .3s ease;
        }

        .modal-wrapper {
            display: table-cell;
            vertical-align: middle;
        }
        .modal-body {
            min-height: auto;
            height: 90%;
        }
    </style>

@stop
@section('content')
    <style>
        .uper{
            margin-top: 40px;
        }
    </style>
<div id="vue-wrapper">
    <div class="modal"><!-- Place at bottom of page --></div>

    <div class="card uper" id="app" >
        <div class="card-heard">
            <div v-if="success" class="alert alert-success alert-dismissible">@{{ success }}</div>

        </div>
        <div class="card-body">


            <form class="">
              <div class="form-row">
                  <div class="form-group col-md-6">
                      <label for="name" class="col-md-6 col-form-label text-md-left">
                          <input id="name" type="text" class="form-control " v-model="smsItem.title" readonly>

                      </label>

                        <div class="col-md-6">
                          <textarea id="comment" class="col-md-12" name="comment" cols="45" rows="5" v-model="smsItem.content"  aria-required="true" readonly>

                          </textarea>

                      </div>

                  </div>
                  <div class="form-group col-md-6">

                      <div class="col-md-6">
                          <textarea id="comment" class="col-md-12" name="comment" style="align-content: flex-start" v-model="contact" cols="45" rows="5" aria-required="true">

                          </textarea>

                      </div>
                      <label for="name" class="col-md-6 col-form-label text-md-left">
                          <a class="btn btn-outline-info  btn-sm"   href="#"  v-on:click="smsvalide()">ENVOYER SMS</a>

                      </label>
                  </div>

              </div>
            </form>


            <div class="card uper" id="app" >
                <div class="card-heard">
                    <nav>
                        <ul class="pagination">
                            <li v-if="pagination.current_page > 1">
                                <a href="#" aria-label="Previous"
                                   @click.prevent="changePage(pagination.current_page - 1)">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li v-for="page in pagesNumber"
                                v-bind:class="[ page == isActived ? 'active' : '']">
                                <a href="#"
                                   @click.prevent="changePage(page)">@{{ page }}</a>
                            </li>
                            <li v-if="pagination.current_page < pagination.last_page">
                                <a href="#" aria-label="Next"
                                   @click.prevent="changePage(pagination.current_page + 1)">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                </div>
                <div class="card-body">


                    <table v-if="items.length!==0" class="table table-hover table-bordered" autosize="1">
                        <tr>
                            <th>N°</th>
                            <th >NOM PRENOM</th>
                             <th class="d-none d-sm-block">PHONE</th>
                            <th>
                                <input type="checkbox"
                                       v-bind:value="0"
                                       v-bind:id="0"
                                       v-on:click="selectAll($event)"
                                       v-model="allSelected"

                                />
                                <label v-bind:for="0"></label>
                            </th>

                        </tr>
                        <tr  v-for="(index , item) in items" >
                            <td>@{{ index + 1 }}</td>
                            <td>@{{ item.nom+ '  ' + item.prenom }}</td>
                             <td class="d-none d-sm-block">@{{ item.phone  }}</td>

                            <td>
                                <input type="checkbox"
                                       :id="item.id"
                                       :value="item.id"
                                       v-on:click="check($event, item)"
                                       v-model="selected"
                                /><label v-bind:for="item.id"></label>
                            </td>
                        </tr>

                    </table>





                    <div v-if="items.length===0" class="alert alert-info  ">
                        Pas de Volontaire
                    </div>



                </div>
                <div class="card-footer">
                    <nav>
                        <ul class="pagination">
                            <li v-if="pagination.current_page > 1">
                                <a href="#" aria-label="Previous"
                                   @click.prevent="changePage(pagination.current_page - 1)">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li v-for="page in pagesNumber"
                                v-bind:class="[ page == isActived ? 'active' : '']">
                                <a href="#"
                                   @click.prevent="changePage(page)">@{{ page }}</a>
                            </li>
                            <li v-if="pagination.current_page < pagination.last_page">
                                <a href="#" aria-label="Next"
                                   @click.prevent="changePage(pagination.current_page + 1)">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>





        </div>
        <div class="card-footer">

        </div>
    </div>




</div>



@endsection

@section('script')
    <!-- Select2 -->
     <script src="../js/jquery.min.js"></script>
     <script src="../js/axios.min.js"></script>
    <script src="../js/vue.js"></script>
    <script src="../js/vue-resource.min.js"></script>

    <script>
        $body = $("body");
        var vm =  new Vue({
            el: '#app',
            data: {
                pagination: {
                    total: 0,
                    per_page: 7,
                    from: 1,
                    to: 0,
                    current_page: 1
                },

                sizetable: "0",
                addpart: "0",
                offset: 4,// left and right padding from the pagination <span>,just change it to see effects
                items: [],

                showModal: false,
                baseurl:"",
                id:"",
                etat:"",
                matricule:"",
                success:0,
                totalRow: 0,
                allSelected: 0,
                contact:'',
                selected: [],
                optionsVolontaires: [],
                optionsVolontairesNumber: [],
                volontaireItem : { 'id': '','nom': '', 'prenom': '', 'email': '',  'phone':''},
                smsItem : { 'id': '','title': '', 'content': ''},

            },
            ready: function () {
                this.baseurl ='{{url('/')}}'+'/';
                 this.id = '{{ $id }}';
                this.fetchItems(this.pagination.current_page);
                this.fgetSms();
                this.contact='';
                this.selected = [];
                this.optionsVolontaires = [];
                this.optionsVolontairesNumber = [];
            },
            computed: {
                isActived: function () {
                    return this.pagination.current_page;
                },
                pagesNumber: function () {
                    if (!this.pagination.to) {
                        return [];
                    }
                    var from = this.pagination.current_page - this.offset;
                    if (from < 1) {
                        from = 1;
                    }
                    var to = from + (this.offset * 2);
                    if (to >= this.pagination.last_page) {
                        to = this.pagination.last_page;
                    }
                    var pagesArray = [];
                    while (from <= to) {
                        pagesArray.push(from);
                        from++;
                    }

                    return pagesArray;
                }
            },
            methods: {
                fetchItems: function (page) {

                    $body.addClass("loading");
                    var mat= this.matricule;
                    if(mat==="") mat=0;
                    var data = {page: page, sms_id: this.id};
                    this.$http.get(this.baseurl+'all-volontaires', data).then(function (response) {
                        //look into the routes file and format your response
                        this.$set('items', response.data.data.data);
                        this.$set('pagination', response.data.pagination);
                        this.totalRow = response.data.pagination.total
                       $body.removeClass("loading");
                    }, function (error) {
                        // handle error
                        $body.removeClass("loading");
                    });
                },
                fgetSms: function () {
                   // alert(this.id);
                    this.$http.get(this.baseurl+'get-sms/'+ this.id).then(function (response) {
                         this.smsItem = response.data;
                       })
                }
                ,
                fshowmodal: function(val){
                    this.items.filter(function (user) {
                        if(user.id === val){
                            vm.user = user;
                           return user;
                        }

                    });
                    vm.showModal = true

                },
                changePage: function (page) {
                    this.pagination.current_page = page;
                    this.fetchItems(page);
                },
                getimg: function(pat){
                    // var pa =  path('public')+ '' + pat
                    //console.log(pa)
                    return  pat ;
                },
            fermer: function () {

                  vm.showModal = false
            },

                check: function(event, item){
                    this.allSelected = 0;
                      console.log(item);
                    if(event.target.checked)
                    {
                        this.selected.push(item.id)
                        this.optionsVolontaires.push(item);
                        this.optionsVolontairesNumber.push(item.phone);
                    }else{
                        this.optionsVolontaires =  this.optionsVolontaires.filter(function (value) {
                            return value.id !==   parseInt(item.id)
                        });
                        this.optionsVolontairesNumber =  this.optionsVolontairesNumber.filter(function (value) {
                            return value !==   parseInt(item.phone)
                        });


                    }
                    this.contact = this.fcontact();

                },
                selectAll: function(event){
                    selected = [];
                    optionsVolontaires = [];
                    optionsVolontairesNumber = [];
                    this.success = 0;
                    if(event.target.checked)
                    {
                            this.allSelected = 1;
                            this.error = 0
                            this.items.forEach(function (value) {
                                selected.push(value.id)
                                optionsVolontaires.push(value);
                                optionsVolontairesNumber.push(value.phone);

                             });
                            this.selected = selected;
                            this.optionsVolontaires= optionsVolontaires;
                            this.optionsVolontairesNumber= optionsVolontairesNumber;
                          //  console.log(this.selected)
                    }else{
                        this.error = 0
                        this.allSelected = 0;
                        this.selected = [];
                        this.optionsVolontaires= [];
                        this.optionsVolontairesNumber= [];

                    }

                    this.contact = this.fcontact();
                    console.log(this.contact);
                    console.log(this.optionsVolontaires);
                },
                fcontact: function(){
                    volontaire='';
                   this.optionsVolontaires.forEach(function (val) {
                       volontaire += val.prenom+' '+val.nom+' - ';
                   });
                   return volontaire
                },
                smsvalide: function(){
                    $body.addClass("loading");
                    selec =[];
                    this.selected.forEach(function (val) {
                        selec.push({user_id: '{{ $user_id }}'  , volontaire_id: val, sms_id: '{{ $id }}'  })
                    });

                    var data = {'numero': this.optionsVolontairesNumber,  data: selec, smsdata: this.smsItem};
                    window.axios.post(this.baseurl+'smsvalide', data).then(
                        function(response){
                            vm.allerros = [];

                            vm.success = response.data.success;
                           location.reload();
                           // vm.fetchItems(this.pagination.current_page);
                           // $body.removeClass("loading");

                        }).catch(function(error) {
                        if (error.response) {
                            vm.allerros = error.response.data.errors;
                            vm.success = false;
                        }

                        $body.removeClass("loading");
                    })
                },

                link: function(){
                    var mat= this.matricule;
                    if(mat==="") mat=0;
                    //   alert(mat)
                    // return this.baseurl+'postulant-pdf?categorie='+  this.categorie+ '&etat='+this.etat+'&prefectureID=' +this.prefectureItem.prefectureID+ "&communeID="+this.communeItem.communeID+'&matricule='+mat ;
                    return this.baseurl+'postulant-pdf/'+this.categorie+'/'+this.etat+'/'+this.prefectureItem.prefectureID+'/'+this.communeItem.communeID+'/'+mat ;
                },

              }
        });

    </script>


@endsection