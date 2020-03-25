int incomingByte = 0;
int state = 0;
char a = 'f'; 
int b = 0;

void setup() {
  //Initialize pin to be output
  Serial.begin(9600);
  pinMode(1, OUTPUT);
  digitalWrite(1, LOW);
}

void loop() {
  //get a
  if(Serial.available() > 0){
    incomingByte = Serial.read();
    
    if(incomingByte == 102){
      a = 'f';
    }
    else if(incomingByte == 48){
      a = 'o';
      b = 0;
    }
    else if(incomingByte == 49){
      a = 'o';
      b = 1;
    }
    else if(incomingByte == 50){
      a = 'o';
      b = 2;
    }
    else{
      Serial.println("Waiting...");
    }
  }
  motor_control(a, b);
  delay(500);
}
//main function to worry about
void motor_control(char a, char b){
  if(a == 'f'){
    digitalWrite(1, LOW);
  }
  else{
    if(b == 0){
      digitalWrite(1, LOW);
    }
    else if(b == 1){
      if(state == 0){
        digitalWrite(1, HIGH);
        state = 1;
      }
      else if(state == 1){
        digitalWrite(1, LOW);
        state = 0;
      }
    }
    else if(b == 2){
      digitalWrite(1, HIGH);
    }
  }
}

void initialize_motor(){
  delay(50);
}
