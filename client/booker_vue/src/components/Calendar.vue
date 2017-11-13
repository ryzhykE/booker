<template>
  <div class="main container-fluid">
     <modal 
        v-if="showModal" 
        :listEvent="listEvent"
        :sentRole="role"
        :sentUser="userss"
        v-on:refresh="getEventsMonth()"
        v-on:close="showModal = false">
     </modal>
    <section id="app" class="section">
      <h3 class="title is-3 shadow" v-text="message"></h3>
      <p class="time shadow" v-text="currentTime"></p>
    </section>
    <div class="boardroom-list">
      <button v-for="room in rooms" @click="setActiveRoom(room.id)" class="btn btn-success"
      :class="{activeRoom: room.id == activeRoomId}" >
        {{room.name}} 
      </button>
    </div>
    <div class="ch-year ">
      <button class="btn btn-primary"v-on:click="counter  = 2">First day Su</button>
      <button class="btn btn-primary" v-on:click="counter  = 1">First day Mon</button>
    </div>
    <div class="change-time">
      <button :class="{currTime: selTimeFormat == 24}" v-on:click="getTimeFormat('24')" class="btn btn-info" >24H</button>
        <button :class="{currTime: selTimeFormat == 'am-pm'}" v-on:click="getTimeFormat('am-pm')" class="btn btn-info">AM-PM </button>     
    </div>
    <div id="app" class="col-md-9">
      <div id="calendar">
        <div class="head"><b class="ltMonth" @click="ltMonth">«</b><b>{{months[currMonth]}} {{currYear}}</b><b class="gtMonth" @click="gtMonth">»</b></div>
          <div v-if="counter == 2">
          <div class="week"><b v-for="day in daysSun">{{day}}</b></div>
              <div class="days ">
                <time v-if="nullWeek !==7" v-for="blank in nullWeek">&nbsp;</time>
                <time v-for="(i, index) in calendar" :class="{currDay: i == currDay}"> 
                   <p class='count-day'>{{i[0]}}</p> 
                    <div class="list-time" v-if="eventsMonth.length>0 ">
                      <p v-if="i[1]" v-for="ev in i[1]" >                     
                        <button class="btn btn-link btn-time" v-on:click="showEvent(ev)"><span class="glyphicon glyphicon-time"></span> {{ev.timeString}}</button>                     
                      </p> 
                    </div>                     
                </time>
              </div>
          </div>
          <div v-else="counter == 1">
          <div class="week"><b v-for="day in daysMon">{{day}}</b></div>
              <div class="days">
                <time v-for="blank in nullWeek1">&nbsp;</time>
                <time v-for="(i, index) in calendar" :class="{currDay: i == currDay}"> 
                     <p class='count-day'>{{i[0]}}</p> 
                    <div class="list-time" v-if="eventsMonth.length>0 ">
                      <p v-if="i[1]" v-for="ev in i[1]" >                     
                       <button class="btn btn-link btn-time" v-on:click="showEvent(ev)"><span class="glyphicon glyphicon-time"></span> {{ev.timeString}}</button>                   
                      </p> 
                    </div>                 
                </time>
              </div>
          </div>
      </div>
      <div class="">
        <div class="booker-but">
          <router-link :to="'/boardroom/'+activeRoomId"><button class="btn btn-btn btn-warning">Book it</button></router-link>
        </div>
        <div class="booker-but">
            <router-link v-if="role == '1'" to="/employeelist">
              <button class="btn btn-btn btn-warning">Employee List</button>
            </router-link>
        </div>
      </div>  
    </div>
      
  </div>
</template>
<script>
import axios from "axios";
import Modal from "./Modal";
export default {
  name: "Calendar",
  props: ["role", "userss"],
  data() {
    return {
      listEvent: {},
      showModal: false,
      message: "Current Time:",
      currentTime: null,
      rooms: "",
      counter: 2,
      activeRoomId: "1",
      typeC: "",
      inst_date: new Date(),
      daysSun: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      daysMon: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      months: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "June",
        "July",
        "Aug",
        "Sept",
        "Oct",
        "Nov",
        "Dec"
      ],
      days: [],
      eventsMonth: [],
      selTimeFormat: 24,
      config: {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      }
    };
  },
  methods: {
    getTimeFormat: function(data) {
      var self = this;
      self.selTimeFormat = data;
      self.addEventsCalendar();
    },
    showEvent: function(event) {
      var self = this;
      self.showModal = true;
      self.listEvent = event;
    },
    updateCurrentTime() {
      var self = this;
      var d = new Date();
      var h;
      if (d.getHours() < 10) {
        h = "0" + d.getHours();
      } else {
        h = d.getHours();
      }
      var m;
      if (d.getMinutes() < 10) {
        m = "0" + d.getMinutes();
      } else {
        m = d.getMinutes();
      }
      var s;
      if (d.getSeconds() < 10) {
        s = "0" + d.getSeconds();
      } else {
        s = d.getSeconds();
      }
      self.currentTime = h + " : " + m + " : " + s;
    },

    addEventsCalendar: function(data) {
      var self = this;
      self.eventsMonth.forEach(function(event) {
        var dateEvStart = new Date(event.time_start);
        var dateEvEnd = new Date(event.time_end);
        var date = new Date(self.currYear, self.currMonth + 1);
        var str = "";
        var start = dateEvStart.getHours();
        var end = dateEvEnd.getHours();
        var startM = new Date(event.time_start).getMinutes();
        var endM = new Date(event.time_end).getMinutes();
        if (self.selTimeFormat == 24) {
          if (dateEvStart.getMinutes() == 0) {
            start += ":" + dateEvStart.getMinutes() + "0-";
          } else {
            start += ":" + dateEvStart.getMinutes() + "-";
          }
          if (dateEvEnd.getMinutes() == 0) {
            end += ":" + dateEvEnd.getMinutes() + "0";
          } else {
            end += ":" + dateEvEnd.getMinutes();
          }
          str = start + end;
          event.timeString = str;
        } else {
          if (+start >= 12) {
            if (+start == 12) {
              var resTime = +start + ":" + startM + " pm - ";
            } else {
              var resTime = +start - 12 + ":" + startM + " pm - ";
            }
          } else {
            var resTime = +start + ":" + startM + " am - ";
          }
          if (+end >= 12) {
            if (+end == 12) {
              var resTimeE = +end + ":" + endM + " pm";
            } else {
              var resTimeE = +end - 12 + ":" + endM + " pm";
            }
          } else {
            var resTimeE = +end + ":" + endM + " am";
          }
          event.timeString = resTime + resTimeE;
        }
      });
    },
    getEventsMonth: function() {
      var self = this;
      self.eventsMonth = [];
      self.error = "";
      var year = self.currYear;
      var month = self.currMonth + 1;
      var room = self.activeRoomId;
      axios
        .get(
          getUrl() + "events/" + room + "/" + year + "/" + month + "/",
          this.config
        )
        .then(function(response) {
          if (response.status == 200) {
            self.eventsMonth = response.data;
            self.addEventsCalendar();
            self.daysInD();
          } else {
            self.error = response.data;
          }
        })
        
    },
    setActiveRoom: function(id) {
      var self = this;
      self.activeRoomId = id;
      self.getEventsMonth();
    },
    getRooms: function() {
      var self = this;
      axios
        .get(getUrl() + "Room/", this.config)
        .then(function(response) {
          if (response.status == 200) {
            self.rooms = response.data;
            self.activeRoomId = self.rooms[0].id;
          } else {
            self.error = response.data;
          }
        })
        .catch(function(error) {
          self.error = error;
        });
    },
    ltMonth: function() {
      var self = this;
      self.inst_date = new Date(self.currYear, self.currMonth - 1);
      self.getEventsMonth();
    },
    gtMonth: function() {
      var self = this;
      self.inst_date = new Date(self.currYear, self.currMonth + 1);
      self.getEventsMonth();
    },
    daysInD: function() {
      var self = this;
      var days = [];
      var countD = new Date(self.currYear, self.currMonth + 1, 0).getDate();
      for (var i = 0; i < countD; i++) {
        days.push([i + 1]);
      }
      self.days = days;
    }
  },
  computed: {
    calendar() {
      var self = this;
      var month = self.days;
      month.forEach(function(day) {
        self.eventsMonth.forEach(function(ev) {
          var evStart = new Date(ev.time_start);
          var d = new Date(self.currYear, self.currMonth, day[0]);
          if (evStart.getDate() == d.getDate()) {
            if (day.length > 1) {
              day[1].push(ev);
            } else {
              day.push([ev]);
            }
          }
        });
      });
      return month;
    },
    currYear() {
      var self = this;
      return self.inst_date.getFullYear();
    },
    currMonth() {
      var self = this;
      return self.inst_date.getMonth();
    },
    currWD() {
      var self = this;
      return self.inst_date.getDay();
    },
    currDay() {
      var self = this;
      const now = new Date();
      if (
        self.inst_date.getMonth() == now.getMonth() &&
        self.inst_date.getFullYear() == now.getFullYear()
      ) {
        return now.getDate();
      }
    },
    nullWeek() {
      var self = this;
      var res = new Date(self.currYear, self.currMonth, 0).getDay() + 1;
      return res;
    },
    nullWeek1() {
      var self = this;
      var res = new Date(self.currYear, self.currMonth, 0).getDay();
      if (res == 0) {
        res = 1;
      }
      return res;
    }
  },
  components: {
    Modal: Modal
  },
  created() {
    this.addEventsCalendar();
    this.getRooms();
    this.getEventsMonth();
    setInterval(() => this.updateCurrentTime(), 1 * 1000);
  }
};
</script>

<style scoped>
.count-day {
  width: 20px;
  margin: 0;
  float: left;
  padding: 0;
}
.btn-time {
  float: right;
  padding: 0;
  min-width: 50px;
  font-size: 14px;
  -webkit-box-shadow: 7px 7px 5px 0px rgba(32, 50, 50, 0.75);
  -moz-box-shadow:    7px 7px 5px 0px rgba(32, 50, 50, 0.75);
  box-shadow:         7px 7px 5px 0px rgba(32, 50, 50, 0.75);
  font-weight: bold;
  color: white;
  
}
.change-time button {
  margin: 0 15px 15px 15px;
  width: 90px;
}
section.section {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 10px;
  background: transparent;
}
h3.is-3,
p.time {
  color: white;
  box-shadow: 0 0 15px white;
}

h3.is-3:not(:last-child) {
  margin: 0;
  padding: 0;
}
.time {
  font-size: 3em;
}
.main {
  margin-top: -10px;
  background: url(/static/img/backcalend.jpg);
  background-size: cover;
}
.activeRoom {
  background-color: #399a87;
  padding-left: 20px;
  padding-right: 20px;
  border: 2px dotted black;
}
.list-time {
  list-style-type: none;
}
.link {
  font-size: 15px;
  color: #ffffff;
}
.booker-but {
  margin-top: 20px;
}
.boardroom-list {
  margin-top: 10px;
  margin-bottom: 20px;
}
.boardroom-list button {
  margin-left: 30px;
  margin-right: 30px;
}
.ch-year {
  padding-bottom: 20px;
}
.ch-year button {
  margin-left: 20px;
  margin-right: 20px;
}
#app {
  margin: 0 auto;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  font-family: sans-serif;
}
.head,
.week,
.days {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}
#calendar {
  box-shadow: 0 1em 10em -2em #000;
  width: 1150px;
  min-height: 700px;
  text-align: center;
  padding-bottom: 20px;
  padding-left: 20px;
}
.week {
  border-bottom: 1px solid rgba(204, 204, 204, 0.3);
  line-height: 2em;
  font-size: 20px;
  color: #0c0c0c;
}
.week b {
  font-weight: normal;
  color: #0c0c0c;
  width: 155px;
  height: 50px;
}
.days {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  text-align: left;
  font-size: 20px;
  color: #0c0c0c;
  align-items: stretch;
}
time {
  width: 155px;
  min-height: 100px;
  border: 1px solid #d4d4d4;
}
.currDay {
  background: #b9b9b9;
  border: 1px solid #245580;
}
.currTime {
  width: 110%;
  border: 2px solid #245580;
  color: #09004b;
}
.head {
  background: rgba(238, 238, 238, 0.3);
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  line-height: 40px;
  height: 60px;
  font-size: 20px;
}
.ltMonth,
.gtMonth {
  cursor: pointer;
  padding: 0 1em;
  background: rgba(238, 238, 238, 0.3);
  font-size: 20px;
  padding-top: 10px;
}
.ltMonth:hover,
.gtMonth:hover {
  background: rgba(238, 238, 238, 0.2);
  color: #f00;
  font-size: 24px;
}
</style>
