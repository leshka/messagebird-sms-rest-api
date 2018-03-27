# Messagebird SMS API client
This implementation uses [MessageBird API](https://github.com/messagebird/php-rest-api) to send SMS messages. 

## Install the application
1. Change MESSAGEBIRD_API_KEY parameter at .env.app
2. Install docker and docker-compose
3. Run build script:
``
./build.sh
``

## API

##### POST /api/sms Sends a message

| Parameter | Type | Description | Required |
| :-------- | :--- | :---------- | :------- |
| recipient | string | The recipient msisdn | Required |
| originator | string | The sender of the message. This can be a telephone number (including country code) or an alphanumeric string. In case of an alphanumeric string, the maximum length is 11 characters | Required |
| message | string | The body of the SMS message | Required |

Example:

``
curl -X POST --data "{\"recipient\": 3197004499527, \"originator\": \"MessageBird\", \"message\": \"test message.\"}" http://localhost:8091/api/sms
``

## References

[MessageBird docs for developers](https://developers.messagebird.com/docs)
