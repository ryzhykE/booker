<template>
<div class="container-fluid form-login">
  <div class="col-xs-offset-1 col-sm-10 col-lg-10">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">{{rooms}}</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class=" col-xs-10 col-sm-10 col-md-10">
            <p class="alert-danger">{{error}}</p>
            <div class="input-group">
              <div class="new-user">
  
                <label>1. Booked for:</label>
                <select v-model="editUser">
                  <option value="" class="default">Select User</option>
                  
                  <option v-for="user in usersList" :value="user.id">{{user.login}}</option>
                </select>
              </div>
            </div>            
            <div class="new-user">
              <label>2. I would like to book this meeting</label>
              <div class="">
                <select v-model="month" class=" col-md-2">
                  <option v-for="(month_name, index) in months" :value="index" :key="index">
                    {{month_name}}
                  </option>
                </select>
                <select v-model="day" class="col-md-2">
                  <option v-for="(day, index) in daysInMonth" :value="day" :key="index">
                    {{day}}
                  </option>
                </select>
                <select v-model="year" class="col-md-2">
                  <option v-for="(numyear, index) in yearsCount" :value="numyear":key="index">
                    {{ numyear }}
                  </option>
                </select>
              </div>
            </div>
            <div class="new-user">
              <label> 3. Specify what the time and end of the meeting. (This will be what people see on the calendar.)</label>
              <div class="col-md-9 ol-xs-offset-3 time-block">
                <div class="col-md-2">
                  <select v-model="timeStartH" class="form-control">
                   <option v-for="h in hoursSelectorSt" :value="h.value">{{h.title}}</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select v-model="timeStartM" class="form-control">
                    <option  value="0">00</option>
                    <option  value="30">30</option>
                  </select>
                </div>
                <div v-if="timeFormat == '12'">
                  <div class="col-md-2">
                    <select v-model="modeEnd" class="form-control">
                      <option  value="am">AM</option>
                      <option  value="pm">PM</option>
                  </select>
                  </div>
                </div>
              </div>
              <div class="col-md-9 ol-xs-offset-3 time-block">
                <div class="col-md-2">
                  <select v-model="timeEndH" class="form-control">
                    <option v-for="s in hoursSelectorEnd" :value="s.value">{{s.title}}</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <select v-model="timeEndM" class="form-control">
                    <option  value="0">00</option>
                    <option  value="30">30</option>
                    </select>
                </div>
                <div v-if="timeFormat == '12'">
                  <div class="col-md-2">
                    <select v-model="modeStart" class="form-control">
                      <option  value="am">AM</option>
                      <option  value="pm">PM</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>           
            <button class="btn btn-info" @click="changeFormat()">TimeFormat</button>
            <div class="new-user">
              <label>4. Enter the specifics for the meeting. (This will be what people see when they click on an event link.)</label>
              <div class="input-group">
                <textarea class="form-control" v-model="description" rows="5" required autofocus></textarea>
              </div>
            </div>
            <div class="new-user">
              <div class="form-group">
                <label>5. Is this going to be a recurring event?</label>
                <div class="radio">
                  <label>
                    <input type="radio" name="recur" id="recur1" value="1" v-model="is_recur" >no
                    </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="recur" id="recur2" value="0" v-model="is_recur" >yes
                    </label>
                </div>
              </div>
            </div>
            <div class="new-user">
              <div class="form-group" v-if="is_recur == 0">
                <label>6. If it is recurring, specify weekly, bi-weekly, or monthly.</label>
                <div class="radio">
                  <label>
                    <input type="radio" name="recur_period"  value="weekly" v-model="recur_period" >weekly
                    </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="recur_period" value="bi-weekly" v-model="recur_period" >bi-weekly
                    </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="recur_period" value="monthly" v-model="recur_period" >monthly
                    </label>
                </div>
                <label> If weekly or bi-weekly, specify the number of weeks for it to keep recurring. 
                        If monthly, specify the number of months. (If you choose "bi-weekly" and put in an odd number of weeks,
                        the computer will round down.)</label>
                <div class="row">
                  <div class="col-sm-1 col-md-1">
                    <input class="form-control num_only" v-model="duration" v-on:keydown="CheckF" oninput="if(this.value>4) this.value = this.value%10  " />
                    <label v-if="recur_period=='weekly'">duration(max 4 weeks)</label>
                    <label v-if="recur_period=='bi-weekly'">duration(max 2 weeks)</label>
                    <label v-if="recur_period=='monthly'">duration(max 1 month)</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <button type="button" class="btn btn-labeled btn-success" v-on:click="addEvent()">
              <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Enter</button>
          <router-link to="/"><button class="btn btn-info">Back</button></router-link>
        </div>
      </div>

    </div>
  </div>
</div>
</template>
<script>
import axios from "axios";
export default {
  beforeRouteUpdate(to, from, next) {
    this.getRoom(to.params.id);
    next();
  },
  data() {
    return {
      timeFormat: "24",
      modeStart: "",
      modeEnd: "",
      timeStartH: "",
      timeStartM: "",
      timeEndH: "",
      timeEndM: "",
      duration: 1,
      recur_period: "",
      is_recur: 1,
      year: "",
      inst_date: new Date(),
      month: "",
      day: "",
      description: "",
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
      yearsCount: [2017, 2018, 2019, 2020],
      users: "",
      user: "",
      editUser: "",
      rooms: "",
      roomid: "",
      error: "",
      oneUser: "",
      config: {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      }
    };
  },

  methods: {
    addEvent: function() {
      var self = this;
      self.error = "";
      if (self.checkInputs()) {
        var date = new Date();
        var sentDate = new Date(
          self.year,
          self.month,
          self.day,
          self.timeStartH,
          self.timeEndM
        );
        if (date > sentDate) {
          self.error = "Date and time must be more current day/time";
          return false;
        }
        var data = new FormData();
        data.append("id_user", self.editUser);
        data.append("id_room", self.roomid);

        if (self.endH == 20 && self.endM == 30) {
          self.error = "End time more than 20:30!";
          return false;
        }
        var timer = {};
        if (self.timeFormat == "12") {
          timer.time_start = new Date(
            self.month +
              1 +
              "/" +
              self.day +
              "/" +
              self.year +
              " " +
              self.timeStartH +
              ":" +
              self.timeStartM +
              " " +
              self.modeStart
          ).getTime();
          timer.time_end = new Date(
            self.month +
              "/" +
              self.day +
              "/" +
              self.year +
              " " +
              self.timeEndH +
              ":" +
              self.timeEndM +
              " " +
              self.modeEnd
          ).getTime();
        } else {
          timer.time_start = new Date(
            self.year,
            self.month,
            self.day,
            self.timeStartH,
            self.timeStartM
          ).getTime();
          timer.time_end = new Date(
            self.year,
            self.month,
            self.day,
            self.timeEndH,
            self.timeEndM
          ).getTime();
          if (self.timeEndH == 20 && self.timeEndM == 30) {
              self.error = "End time more than 20:30!";
              return false;
            }
               if (timer.time_start > timer.time_end) {
            self.error = "End of event after start";
            return false; }
          
        }   
          if (
            self.timeStartH + "/" + self.timeStartM ===
            self.timeEndH + "/" + self.timeEndM
          ) 
          {
            self.error = "Beginning can not be equal to the end";
            return false;
          }
        data.append("time_start", timer.time_start);
        data.append("time_end", timer.time_end);
        data.append("description", self.description);
        if (self.is_recur == 0) {
          data.append("recur_period", self.recur_period);
          data.append("duration", self.duration);
        }
        axios
          .post(getUrl() + "events/", data, self.config)
          .then(function(response) {
            if (response.status == 1) {
              self.error = "Thanks for the application";
            } else {
              self.error = response.data;
            }
          })
          .catch(function(error) {
            self.error = error;
          });
      }
    },
    checkInputs: function() {
      var self = this;
      if (!self.editUser) {
        self.error = "Select User";
        return false;
      }
      if (!self.year || !self.month || !self.day) {
        self.error = "Select Date";
        return false;
      }

      if (self.timeStartH == 20 && self.timeEndM == 30) {
        self.error = "End time more than 20:00!";
        return false;
      }
      if (
        !self.timeStartH ||
        !self.timeStartM ||
        !self.timeEndH ||
        !self.timeEndM
      ) {
        self.error = "Select time";
        return false;
      }

      if (!self.description) {
        self.error = "Check description";
        return false;
      }
      if (self.is_recur == 0) {
        if (!self.recur_period) {
          self.error = "Check  recurring";
          return false;
        }
        if (self.duration == "") {
          self.error = "Check duration ";
          return false;
        }
      }
      return true;
    },
    checkUserFun: function() {
      var self = this;
      if (localStorage["user"]) {
        self.user = JSON.parse(localStorage["user"]);
        axios
          .get(getUrl() + "user/" + self.user.id + "/")
          .then(function(response) {
            console.log(response.data.login)
            self.oneUser = response.data.login
            if (response.status == 200) {
              if (self.user.hash === response.data.hash) {
                if (response.data.id_role == "1") {
                  self.user.role = response.data.role;
                  self.getUser();
                }
              }
            } else {
              self.$router.push("/");
            }
          });
      } else {
        self.$router.push("/");
      }
    },
    changeFormat: function() {
      var self = this;
      self.timeStartH = "";
      self.timeEndH = "";
      if (self.timeFormat == "12") {
        self.timeFormat = "24";
      } else {
        self.timeFormat = "12";
      }
    },
    CheckF: function(event) {
      if (
        this.recur_period == "weekly" &&
        (event.keyCode < 49 || event.keyCode > 51) &&
        (event.keyCode < 97 || event.keyCode > 99)
      ) {

        //self.duration = event.keyCode
        event.preventDefault();
      } else if (
        this.recur_period == "bi-weekly" &&
        (event.keyCode < 49 || event.keyCode > 50) &&
        (event.keyCode < 97 || event.keyCode > 98)
      ) {
        event.preventDefault();
      } else if (this.recur_period == "monthly" && event.keyCode != 49) {
        event.preventDefault();
      }
    },
    getUser: function() {
      var self = this;
      axios
        .get(getUrl() + "user/", self.config)
        .then(function(response) {
          if (response.data !== false) {
            self.users = response.data;
          }
        })
        .catch(function(error) {
          self.error = error;
        });
    },
    getRoom: function(id) {
      var self = this;
      axios
        .get(getUrl() + "Room/" + id + "/", self.config)
        .then(function(response) {
          if (response.status == 200) {
            self.rooms = response.data.name;
            self.roomid = response.data.id;
          } else {
            self.error = response.data;
          }
        })
        .catch(function(error) {
          self.error = error;
        });
    }
  },
  computed: {
    usersList() {
      var self = this;
      if (self.users.length != 0) {
        return self.users;
      } else {
        var arr = [];
        arr.push(self.oneUser);
        return arr;
      }
    },
    hoursSelectorSt() {
      var self = this;
      var hours = [];
      if (self.timeFormat == "12") {
        for (var i = 1; i <= 11; i++) {
          hours.push({
            value: i,
            title: i
          });
        }
      } else {
        for (var i = 8; i <= 19; i++) {
          hours.push({
            value: i,
            title: i
          });
        }
      }
      return hours;
    },
    hoursSelectorEnd() {
      var self = this;
      var hours = [];
      if (self.timeFormat == "12") {
        for (var i = 1; i <= 12; i++) {
          hours.push({
            value: i,
            title: i
          });
        }
      } else {
        for (var i = 8; i <= 20; i++) {
          hours.push({
            value: i,
            title: i
          });
        }
      }
      return hours;
    },
    daysInMonth: function() {
      var self = this;
      var days = [];
      var countD = new Date(self.year, self.month + 1, 0).getDate();
      for (var i = 0; i < countD; i++) {
        days.push(i + 1);
      }
      return days;
    },
    recurring: function() {
      var self = this;
      if (self.is_recur == "1") {
        self.is_recur = 1;
      } else {
        self.is_recur = 0;
      }
    }
  },
  created() {
    this.checkUserFun();
    this.getRoom(this.$route.params.id);
  }
};
</script>
<style scoped>
.form-login {
  background: url(/static/img/room4.jpg);
}
.new-user {
  font: 1.3em sans-serif;
  margin-top: 20px;
  margin-bottom: 20px;
  padding-bottom: 20px;
}
.new-user p {
  text-align: left;
}
.time-block {
  margin-bottom: 20px;
  margin-top: 10px;
}
</style>
