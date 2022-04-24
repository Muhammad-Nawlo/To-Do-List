import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { Router } from '@angular/router';
import { UserService } from '../services/user.service';

@Component({
  selector: 'signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css'],
})
export class SignupComponent implements OnInit {
  constructor(private userSerivce: UserService, private router: Router) {}

  ngOnInit(): void {
    console.log(this.userSerivce.isLoggedIn());
     
  }
  signup(data: NgForm) {
    this.userSerivce.signup(data.value).subscribe(
      (response: any) => {
        if (response.msg == 'ok') {
          this.router.navigate(['/login']);
          return true;
        } else {
          data.form.setErrors({ error: response.info });  
          console.log(response.info);
          return false;
        }
      },
      (error) => {
        console.log(error);
        return false;
      }
    );
  }
}
