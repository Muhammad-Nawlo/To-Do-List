import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { JwtHelperService } from '@auth0/angular-jwt';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(private httpClient:HttpClient ) {}

  private httpAuthHeader(withAuth = false) {
    if (withAuth) {
      const token: any = localStorage.getItem('access-token');
      const httpOptions: any = {
        headers: new HttpHeaders({
          Authorization: 'Bearer ' + token,
          'Access-Control-Allow-Origin': '*',
          'Content-Type': 'application/json; charset=UTF-8',
        }),
      };
      return httpOptions;
    } else {
      return {};
    }
  }

  login(userInfo: any) {
    return this.httpClient.post(
      environment.userUrl + 'login',
      userInfo,
      this.httpAuthHeader(true)
    );
  }

  signup(userInfo: any) {
    return this.httpClient.post(
      environment.userUrl + 'signup',
      userInfo,
      this.httpAuthHeader(true)
    );
  }

  logout() {
    localStorage.removeItem('accessToken');
  }

  isLoggedIn() {
    let accessToken: any = localStorage.getItem('accessToken');
    return !new JwtHelperService().isTokenExpired(accessToken);
  }

  getUser() {
    let accessToken: any = localStorage.getItem('accessToken');
    if (accessToken)
      return new JwtHelperService().decodeToken(accessToken).user;
  }
}
