<template>
  <div class="main container">
      <div class="row form-login">
        <div class="col-xs-offset-3 col-sm-8 col-lg-6">
          <div class="panel panel-primary">
          <div class="panel-heading">
          <h3 class="panel-title">Authorization</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-xs-offset-2 col-xs-8 col-sm-8 col-md-8 login-box">
                <p class="alert-danger">{{error}}</p>
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input v-model="login" type="text" class="form-control" placeholder="Login" name='login' required autofocus />
                </div>
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input v-model="pass" type="password" class="form-control" name = "password" placeholder="Password" required />
                </div>
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <button type="button" class="btn btn-labeled btn-success" v-on:click="loginUser()">
            <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Enter</button>
          </div>
        </div>
      </div>
    </div>
    <div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  name: "Main",
  data() {
    return {
      error:'',
      login: "",
      pass: "",
      id: "",
      checkUser: "",
      role: "",
      config: {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      }
    };
  },
   methods: {
      loginUser: function() {
      var self = this;
      if (self.pass.length < 4) {
         self.error = "Password should be at least 4 characters"
        return false
        }
         if (self.login.length < 4) {
         self.error = "Login more 4 characters"
         return false
        }
      axios
        .put(
          getUrl()+'User/',
          {
            login: self.login,
            pass: self.pass
          }
        )
        .then(function(response) {
          self.error = "";
          if (response.data.id && response.data.hash) {
            self.$parent.user.id = response.data.id
            self.$parent.user.hash = response.data.hash 
            self.$parent.user.login = response.data.login
            localStorage['user'] = JSON.stringify(self.$parent.user)             
            self.$parent.user.role = response.data.role
            self.$parent.checkUser=1
          } 
          else {
            self.error = "Password or login wrong";           
          }
        })
        .catch(function(error) {
          self.error = "Password or login wrong"
        });
    },
  created() {
  }
  },

  
   
};
</script>

<style scoped>
.main {
   background: url(/static/img/calendar.jpg) no-repeat center center fixed;
   background-size: cover; 
}
.form-login {
  margin-top: 20px;
}



</style>

