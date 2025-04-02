import {useRouter} from 'vue-router'

const router = useRouter()

const { protocol, hostname } = window.location;
const baseURL = process.env.VUE_APP_DOMAIN
const LINKEDIN_REDIRECTION_URI = process.env.VUE_APP_DOMAIN+"/auth/signin";
const LINKEDIN_SCOPE = "openid profile email"


const getCode = () => {
    const linkedInAuthUrl = new URL("https://www.linkedin.com/oauth/v2/authorization");

    /** @todo: remove the const in the end */
    const linkedInClientId = process.env.VUE_APP_LINKEDIN_CLIENT_ID

    linkedInAuthUrl.searchParams.append("response_type", "code");
    linkedInAuthUrl.searchParams.append("client_id", linkedInClientId);
    linkedInAuthUrl.searchParams.append("redirect_uri", LINKEDIN_REDIRECTION_URI);
    linkedInAuthUrl.searchParams.append("scope", LINKEDIN_SCOPE);

    window.location.href = linkedInAuthUrl.toString()
}

const getUserInfo = async (access_token) => {

    const response = await fetch(baseURL + '/auth/signin/linkedinfo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({"access_token": access_token})
    })
    return response.json();
}

const getAccessToken = async (code) => {

    const response = await fetch(baseURL + '/auth/signin/linkedaccess', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
      },
      body: JSON.stringify({ "code": code })
    })
    return response.json();
}

export {getCode, getAccessToken, getUserInfo};