import {useRouter} from 'vue-router'

const router = useRouter()

const { protocol, hostname } = window.location;
const baseURL = protocol + '//' + hostname

const LINKEDIN_CLIENT_ID = "77rjui4xjnl5or";
const LINKEDIN_REDIRECTION_URI = "http://localhost:8080/auth/signin";
const LINKEDIN_SCOPE = "openid profile email"


const getCode = () => {

    const linkedInAuthUrl = new URL("https://www.linkedin.com/oauth/v2/authorization");

    linkedInAuthUrl.searchParams.append("response_type", "code");
    linkedInAuthUrl.searchParams.append("client_id", LINKEDIN_CLIENT_ID);
    linkedInAuthUrl.searchParams.append("redirect_uri", LINKEDIN_REDIRECTION_URI);
    linkedInAuthUrl.searchParams.append("scope", LINKEDIN_SCOPE);

    window.location.href = linkedInAuthUrl
}

const getUserInfo = async (access_token) => {

    const response = await fetch(baseURL + '/auth/signin/linkedinfo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({"access_token": access_token})
    })
    return await response.json();
}

const getAccessToken = async (code) => {

    const response = await fetch(baseURL + '/auth/signin/linkedaccess', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({ code })
    })
    const data = await response.json();

    return getUserInfo(data['access_token'])
}



export {getCode, getAccessToken, getUserInfo};