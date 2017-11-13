<template>
    <div class="row">
        <div class="col-lg-5 ">
            <h4>Register user</h4>
            <div v-if="error !== ''" class="alert alert-danger">
                {{ error }}
            </div>
                <div class="form-group" v-on:submit.prevent="registration">
                    <label class="control-label col-sm-2" for="login">Login: </label>
                    <div class="col-sm-10">
                        <input v-model="login" type="text" class="form-control" id="login" placeholder="Enter login" name="login">
                    </div>
                    <p class="help-block" v-show="!validation.login">Login cannot be empty </p>
                </div>

                <div class="form-group"  v-on:submit.prevent="registration">
                    <label class="control-label col-sm-2" for="email">Email: </label>
                    <div class="col-sm-10">
                        <input v-model="email" type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                    </div>
                     <p class="help-block" v-show="!validation.email">Please provide a valid email address. </p>
                </div>
                <div class="form-group"  v-on:submit.prevent="registration">
                    <label class="control-label col-sm-2" for="pass">Password: </label>
                    <div class="col-sm-10">
                        <input v-model="pass" type="password" class="form-control" id="pass" placeholder="Enter password" name="pass">
                    </div>
                    <p class="help-block" v-show="!validation.pass">Password cannot be empty </p>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="pass_confirm">Password Conferm: </label>
                    <div class="col-sm-10">
                        <input v-model="pass_confirm" type="password" class="form-control" id="pass_confirm" placeholder="Conferm password" name="pass">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button v-on:click="registration()" type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
var emailRE = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
import axios from "axios";
export default {
  name: "register",
  data() {
    return {
      error: "",
      success: "",
      login: "",
      email: "",
      pass: "",
      pass_confirm: "",
      config: {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        }
      }
    };
  },
  computed: {
    validation: function() {
      return {
        login: !!this.login.trim(),
        pass: !!this.pass.trim(),
        email: emailRE.test(this.email)
      };
    },
    isValid: function() {
      var validation = this.validation;
      return Object.keys(validation).every(function(key) {
        return validation[key];
      });
    }
  },
  methods: {
    registration: function() {
      var self = this;
      self.error = "";
      if (self.email && self.login && self.pass && self.pass_confirm) {
        if (self.pass.length < 5) {
          self.error = "Password should be at least 4 characters";
          return false;
        }
        if (self.login.length < 4) {
          self.error = "Login more 4 characters";
          return false;
        }
        if (self.pass != self.pass_confirm) {
          self.error = "Password fields do not match";
          return false;
        }
        if (self.isValid) {
          var data = new FormData();
          data.append("email", self.email);
          data.append("login", self.login);
          data.append("pass", self.pass);
          axios
            .post(getUrl() + "User/", data, self.config)
            .then(function(response) {
              if (response.data === "Register success") {
                self.$parent.error = "User add"
                self.$router.push({ path: "/employeelist/" });
                self.$parent.getUsersList();
              } else {
                self.error = response.data;
              }
            })
            .catch(function(error) {
              self.error = response.data;
              //console.log(error);
            });
        } else {
          self.error = "Enter field!";
        }
      }
    }
  }
};
</script>

<style scoped>
</style>
