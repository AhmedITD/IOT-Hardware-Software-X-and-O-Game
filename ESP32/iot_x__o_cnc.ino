#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
//Gloable
String body, PostRequestUrl, tokenData;
int httpResponseCode;
// Wi-Fi Credentials
const char* ssid = "Basheer basheer";
const char* password = "1020304050";
WiFiClient client;
HTTPClient http;
IPAddress staticIP(192, 168, 43, 62);
IPAddress gateway(192, 168, 43, 213);
IPAddress subnet(255, 255, 255, 0);
IPAddress dns(192, 168, 43, 123);
void setup()
{
Serial.begin(115200);
delay(1000);
if (WiFi.config(staticIP, gateway, subnet, dns, dns) == false) 
{
  Serial.println("Configuration failed.");
}
// Serial.println("\n\nConecting\n");
setup_wifi();
}
void loop() 
{
  //Check WiFi connection
  if (WiFi.status() == WL_CONNECTED) 
  {
    //Auth POST
    PostRequestUrl = "http://192.168.43.213:8000/api/apiLogin";
    http.begin(client, PostRequestUrl);
    http.addHeader("content-Type", "application/json");
    body = "{\"name\":\"ahmed\",\"password\":\"123456\"}";
    httpResponseCode = http.POST(body);
    tokenData = http.getString();
    StaticJsonDocument<200> doc;
    DeserializationError error = deserializeJson(doc, tokenData);
    String token = doc["token"];
    http.end();  
    //Data GET
    const String requestUrl = "http://192.168.43.213:8000/api/game";
    http.begin(client, requestUrl);
    http.addHeader("Authorization", "Bearer " + token);
    httpResponseCode = http.GET();
    String GETpayload = http.getString();
    http.end();
    if (httpResponseCode == HTTP_CODE_OK) 
    {
      DynamicJsonDocument doc(1024);
      deserializeJson(doc, GETpayload);
      JsonObject obj = doc.as<JsonObject>();
      String state = obj[String("state")];
        //array
        JsonArray signatureFileContentArray = obj[String("data")][String("godeContent")];
        // POST
        PostRequestUrl = "http://192.168.43.213:8000/api/game";
        http.begin(client, PostRequestUrl);
        http.addHeader("content-Type", "application/json");
        http.addHeader("Authorization", "Bearer " + token);
        body = "{\"state\":\"Get Req Recived, Proccing\",\"code\":\"1\"}";
        httpResponseCode = http.POST(body);
        http.end();
        for (JsonVariant value : signatureFileContentArray) 
        {
          //thise holl delay system is on demo version :)
          String signatureValue = value.as<String>();
          Serial.println(signatureValue);
          int Speed = 500;//mm per min as a min speed + Assuming that the acceleration is zero
          int xIndex, yIndex;
          float a_delay, initialXValue, initialYValue, xValue, yValue, deltaX, deltaY;
          xIndex = signatureValue.indexOf('X');
          yIndex = signatureValue.indexOf('Y');    
          //Extract X and Y values if 'X' and 'Y' are found
          if (xIndex != -1 && yIndex != -1) 
          {
            xValue = getValue(signatureValue, xIndex);
            yValue = getValue(signatureValue, yIndex);
            deltaX = xValue - initialXValue;
            deltaY = yValue - initialYValue;
            if (deltaX > deltaY && deltaX > 0.500 && deltaX < -0.500)
            {
              deltaY = (float)(deltaX / Speed) * 60; 
            } else if(deltaY > deltaX && deltaY > 0.500 && deltaY < -0.500)
            {
              deltaY = (float)(deltaY / Speed ) * 60;
            } else 
            {
              a_delay = 50;   
            }
            delay(a_delay);        
          } else
          {
            delay(300);
          }
          delay(500);//
          initialXValue = xValue;
          initialYValue = yValue;
        }
      if (state == "200")
      {  
      PostRequestUrl = "http://192.168.43.213:8000/api/game";
      http.begin(client, PostRequestUrl);
      http.addHeader("content-Type", "application/json");
      http.addHeader("Authorization", "Bearer " + token);
      body = "{\"state\":\"end Proccing\",\"code\":\"2\"}";
      httpResponseCode = http.POST(body);
      http.end();  
      }
    } else 
    {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }
  } else 
  {
    Serial.println("WiFi Disconnected!!");
  }
delay(500);
}
void setup_wifi() 
{
  // Serial.print("Connecting to:");
  // Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) 
  {
    delay(500);
    // Serial.print(".");
  }
  // Serial.println("WiFi connected");
  // Serial.println("IP address: ");
  // Serial.println(WiFi.localIP());
}
float getValue(String command, int startIndex) 
{
  int endIndex = startIndex + 1;
  while (endIndex < command.length() && (isdigit(command.charAt(endIndex)) || command.charAt(endIndex) == '.')) 
  {
    endIndex++;
  }
  String valueStr = command.substring(startIndex + 1, endIndex);
  return valueStr.toFloat();
}