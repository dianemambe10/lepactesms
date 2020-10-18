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
<div id="vue-wrapper">
    <div class="modal"><!-- Place at bottom of page --></div>

    <div class="card uper" id="app" >
        <div class="card-heard">

        </div>
        <div class="card-body">
           <div style="margin: 0.3em">
               <a class="btn btn-outline-info "  href="{{ url('/') }}/sms/create">Nouveau SMS</a>

           </div>

                <table v-if="items.length!==0" class="table table-hover table-bordered">
                    <tr>
                        <th>N°</th>
                        <th>TITRE</th>
                        <th>CONTENU</th>
                        <th>ACTION</th>
                    </tr>
                    <tr  v-for="(index , item) in items" >
                        <td>@{{ index + 1 }}</td>
                        <td>@{{ item.title}}</td>
                        <td>@{{ item.content  }}</td>
                         <td>
                            <a class="btn btn-outline-info  btn-sm"  href="@{{ baseurl }}send-sms/@{{ item.id }}" >Send</a>
                          </td>

                    </tr>

                </table>





                <div v-if="items.length===0" class="alert alert-info  ">
                    Pas de Client APi
                </div>



        </div>
        <div class="card-footer">
            <nav>
                <ul class="breadcrumb">
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



@endsection

@section('script')
    <!-- Select2 -->
     <script src="js/jquery.min.js"></script>
     <script src="js/axios.min.js"></script>
    <script src="js/vue.js"></script>
    <script src="js/vue-resource.min.js"></script>

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
                totalRow: 0,
                allSelected: 0,
                selected: [],
                optionsVolontaires: [],
                volontaireItem : { 'id': '','nom': '', 'prenom': '', 'email': '',  'phone':''},

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
                    this.$http.get(this.baseurl+'all-sms', data).then(function (response) {
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

                check: function(event){
                    this.allSelected = 0;
                    //  console.log(this.optionParticipant.length)
                    if(event.target.checked)
                    {

                    }else{
                           }
                    // console.log(this.selected)
                },
                selectAll: function(event){
                    selected = [];
                    optionsVolontaires = [];
                    this.success = 0;
                    if(event.target.checked)
                    {

                            this.allSelected = 1;
                            this.error = 0
                            this.items.forEach(function (value) {
                                selected.push(value.id)
                                optionsVolontaires.push(value);

                             });
                            this.selected = selected;
                            this.optionsVolontaires= optionsVolontaires;
                        console.log(this.optionsVolontaires);

                    }else{
                        this.error = 0
                        this.allSelected = 0;
                        this.selected = [];
                    }

                },



                link: function(){
                    var mat= this.matricule;
                    if(mat==="") mat=0;
                    //   alert(mat)
                    // return this.baseurl+'postulant-pdf?categorie='+  this.categorie+ '&etat='+this.etat+'&prefectureID=' +this.prefectureItem.prefectureID+ "&communeID="+this.communeItem.communeID+'&matricule='+mat ;
                    return this.baseurl+'postulant-pdf/'+this.categorie+'/'+this.etat+'/'+this.prefectureItem.prefectureID+'/'+this.communeItem.communeID+'/'+mat ;
                },
                addparticipant: function(val, index){
                    // alert( val.utID);
                    $body.addClass("loading");

                    this.participantItem.utilisateurID = val.utID;
                    this.participantItem.prefectureID = $('#prefectureID').val();
                    this.participantItem.formationID = $('#formationID').val();
                    //console.log(this.participantItem);

                    window.axios.post(this.baseurl+'add-participant', this.participantItem)
                        .then(function (response) {
                            $body.removeClass("loading");
                            console.log(response.data);
                            vm.items.splice(index, 1);
                            vm.addpart = 1;
                            vm.sizetable = vm.items.length;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                    //  console.log(val.utID);
                }
                ,

                addparticipantTous: function(){
                    $body.addClass("loading");

                    this.trakerFilterItem.categorieID = $('#categorieID').val();
                    this.trakerFilterItem.prefectureID = $('#prefectureID').val();
                    this.trakerFilterItem.formationID = $('#formationID').val();
                    // console.log(this.trakerFilterItem);
                    this.items.forEach(function (element) {
                        element.formationID =$('#formationID').val();

                    })

                    window.axios.post('../addparticipanttous' ,{ participant: this.items}).then(
                        function (response) {
                            //console.log(response.data);
                            //  vm.getCandidature($('#categorieID').val(), $('#prefectureID').val(), $('#formationID').val());
                            vm.addpart = vm.items.length;
                            vm.items.splice(0, vm.items.length);
                            // this.sizetable =

                            vm.fetchItems(this.pagination.current_page);
                            //vm.addpart = 1;
                            $body.removeClass("loading");
                            // setTimeout("window.location.href='../formation-consulte/"+$('#formationID').val()+"';", 4000);

                        })
                        .catch(function (error) {
                            console.log(error);
                            $body.removeClass("loading");
                        });
                }
            }
        });

    </script>


@endsection