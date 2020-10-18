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
          Nouveau SMS
        </div>
        <div class="card-body">
            <div style="margin: 0.5em">
                <a class="btn btn-info" href="{{URL('sms')}}"  >Retour</a>

            </div>
            <form method="POST"  action="{{URL('sms-save')}}" class="form-vertical" @submit.prevent="onSubmit" >


                    {{ csrf_field() }}

                    <div  :class="['form-group', 'col-xs-12', allerros.title ? 'has-error' : '']">
                        <label for="nom">TITRE</label>
                        <input type="text" name="title" autofocus="autofocus" class="form-control" placeholder="nom" v-model="sms.title">
                        <span v-if="sms.title" :class="['label label-danger']">@{{ allerros.title[0] }}</span>

                    </div>
                <div  :class="['form-group', 'col-xs-12', allerros.content ? 'has-error' : '']">
                        <label for="nom">CONTENU</label>
                       <textarea id="content" class="col-md-12" name="content" style="align-content: flex-start" v-model="sms.content" cols="45" rows="5" aria-required="true">

                          </textarea>
                        <span v-if="sms.content" :class="['label label-danger']">@{{ allerros.title[0] }}</span>

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
        const vm =  new Vue({
            el: '#app',
            data: {
                sizetable: "0",
                addpart: "0",
                offset: 4,// left and right padding from the pagination <span>,just change it to see effects
                showModal: false,
                baseurl:"",
                id:"",
                etat:"",
                matricule:"",
                totalRow: 0,
                allerros: [],
                sms:{'title': '' , 'content': ''}

            },
            ready: function () {
                this.baseurl ='{{url('/')}}'+'/';

            },
            computed: {

            },
            methods: {


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
                    formdata.append('title', this.sms.title);
                    formdata.append('content', this.sms.content);
                    console.log(e.target.action);

                    axios.post(e.target.action, formdata).then(
                        function(response){
                            this.allerros = [];
                            $body.removeClass("loading");
                            vm.success = response.data.success
                        }).catch(function(error){
                        if(error.response){
                            vm.allerros =error.response.data.errors;
                            console.log(this.allerros);
                            vm.success = 0;
                        }
                        $body.removeClass("loading");
                    });
                }
            }
        });

    </script>


@endsection