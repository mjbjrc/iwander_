<!-- <template>
<div class="container">
  <div class="row iwander-row">
    <div class="col-lg-12">
      <h2>iWander</h2>
      <h3>{{this.$route.query.duration}} in {{itinerary.destination}}</h3>
      <button class="btn btn-primary float-right" @click="showModal(plan.event_id)" >
          Download
      </button>
    </div>
  </div>
  <div class="row iwander-row">
    <div class="col-lg-12">
      <h2>Timeline</h2>
    </div>
    <div class="col-lg-12" v-for="date in dates">
      <h2>{{date.textFormat}}</h2>
    </div>
  </div>
</div>

</template>
<script>
var moment = require('moment');

export default{
  name: 'convertToPdf',
  props: ['itinerary_id','duration'],
  components: {},
  data(){
    return{
      moment: moment,
      itinerary: '',
      dates: [],
      plans: [],
      events: [{date: '', events: []}]

    }
  },
  mounted(){},
  created(){
    let app = this;
    console.log(this.$route.query.itinerary_id);
    let token = localStorage.getItem("token");
    axios.get(`/api/getItineraryByID/${this.$route.query.itinerary_id}`,{
      headers: { Authorization: "Bearer " + token }
    })
    .then(function(response){
      app.itinerary = response.data.data;
      app.getDates();
    })
    .catch(function(error){
      console.log(error);
    });
  },
  methods: {
    getDates(){
      let app = this;
      let tempDates = [];
      let sDate = moment(app.itinerary.start_date);
      let eDate = moment(app.itinerary.end_date);
      let day = 1;
      while(sDate <= eDate){
        app.dates.push({textFormat: "Day " + day + " : " + moment(sDate).format('MMM Do YYYY'), numFormat: moment(sDate).format('YYYY-MM-DD')});
        sDate = moment(sDate).add(1, 'days');
        day++;
      }
      //remove empty arrays
      console.log(app.dates);
      app.getEvents();
    },
    getEvents(){
      let app = this;
        let token = localStorage.getItem("token");
        axios.get(`/api/getAttractionsByEvent/${this.$route.query.itinerary_id}`,{
          headers: {'Authorization': 'Bearer ' + token},
        })
        .then(response => {
          let results = response.data.data;
          results.forEach((event)=>{
            app.dates.forEach((date)=>{
              if(date.numFormat === event.date){
                app.events.push({date: date.numFormat, events: {event}});
              }
            })
            if(app.events.length == 0){
              console.log("NO EVENT");
            }
          })
        })
        .catch(function(error){
          console.log(error);
        });
    }
  }
}

</script>
<style>
</style> -->
