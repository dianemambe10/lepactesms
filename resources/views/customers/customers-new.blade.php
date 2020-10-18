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
            height: 100%;
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
        }
    </style>

@stop
@section('content')
    <style>
        .uper{
            margin-top: 40px;
        }
    </style>
<div   id="app">
    <div class="modal"><!-- Place at bottom of page --></div>
    <div class="row row justify-content-center">
        <div class="col-md-6 col-md-offset-2">

    <div class="card uper" >
        <div class="card-heard">
          Nouveau Client
        </div>
        <div class="card-body">
            <div style="margin: 0.5em">
                <a class="btn btn-outline-info btn-sm" href="{{URL('customers')}}"  >Retour </a>

            </div>
            <form method="POST"  action="{{URL('customers-save')}}" class="form-vertical" @submit.prevent="onSubmit" >


                    {{ csrf_field() }}

                    <div  :class="['form-group', 'col-xs-12', allerros.nom ? 'has-error' : '']">
                        <label for="nom">NOM</label>
                        <input type="text" name="nom" autofocus="autofocus" class="form-control" placeholder="nom" v-model="customers.nom">
                        <span v-if="allerros.nom" :class="['label label-danger']">@{{ allerros.nom[0] }}</span>

                    </div>
                    <div  :class="['form-group', 'col-xs-12', allerros.prenom ? 'has-error' : '']">
                        <label for="prenom">PRENOM</label>
                        <input type="text" name="prenom" autofocus="autofocus" class="form-control" placeholder="prenom" v-model="customers.prenom">
                        <span v-if="allerros.prenom" :class="['label label-danger']">@{{ allerros.prenom[0] }}</span>
                    </div>
                    <div  :class="['form-group', 'col-xs-12', allerros.email ? 'has-error' : '']">
                        <label for="email">EMAIL</label>
                        <input type="text" name="email" autofocus="autofocus" class="form-control" placeholder="email" v-model="customers.email">
                        <span v-if="allerros.email" :class="['label label-danger']">@{{ allerros.email[0] }}</span>
                    </div>
                    <div  :class="['form-group', 'col-xs-12', allerros.telephone ? 'has-error' : '']">
                        <label for="telephone">N° TELEPHONE</label>
                        <input type="text" id="telephone" autofocus="autofocus" class="form-control" placeholder="N° telephone" v-model="customers.telephone">
                        <span v-if="allerros.telephone" :class="['label label-danger']">@{{ allerros.telephone[0] }}</span>

                    </div>
                <button type="submit"  class="btn btn-primary">ENREGISTRER</button>


            </form>



        </div>
        <div class="card-footer">


        </div>
    </div>
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
         vm =  new Vue({
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
                optionsPrefecture: [],
                showModal: false,
                baseurl:"",
                id:"",
                etat:"",
                matricule:"",
                totalRow: 0,
                allerros: [],
                customers:{'nom': '' , 'prenom': '', 'email': '' ,'telephone': ''}

            },
            ready: function () {
                this.baseurl ='{{url('/')}}'+'/';

                this.fetchItems(this.pagination.current_page);
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
                    var data = {page: page};
                    this.$http.get(this.baseurl+'all-customers', data).then(function (response) {
                        //look into the routes file and format your response
                        this.$set('items', response.data.data.data);
                         //console.log(response.data.data.data)
                        this.$set('pagination', response.data.pagination);
                        this.totalRow = response.data.pagination.total
                       $body.removeClass("loading");
                    }, function (error) {
                        // handle error
                        $body.removeClass("loading");
                    });
                },

                fshowmodal: function(){

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
                link: function(){
                    var mat= this.matricule;
                    if(mat==="") mat=0;
                    //   alert(mat)
                    // return this.baseurl+'postulant-pdf?categorie='+  this.categorie+ '&etat='+this.etat+'&prefectureID=' +this.prefectureItem.prefectureID+ "&communeID="+this.communeItem.communeID+'&matricule='+mat ;
                    return this.baseurl+'postulant-pdf/'+this.categorie+'/'+this.etat+'/'+this.prefectureItem.prefectureID+'/'+this.communeItem.communeID+'/'+mat ;
                },

                onSubmit: function (e) {
                    $body.addClass("loading");
                    formdata = new FormData();
                    formdata.append('nom', this.customers.nom);
                    formdata.append('prenom', this.customers.prenom);
                    formdata.append('email', this.customers.email);
                    formdata.append('telephone', this.customers.telephone);
                    console.log(e.target.action);

                    axios.post(e.target.action, formdata).then(
                        function(response){
                            this.allerros = [];
                            $body.removeClass("loading");
                            vm.success = response.data.success
                        }).catch(function(error){
                        if(error.response){
                            vm.allerros =error.response.data.errors;
                          // alert(vm.allerros.nom[0]);
                            vm.success = 0;
                        }
                        $body.removeClass("loading");
                    });
                }
            }
        });

    </script>


@endsection