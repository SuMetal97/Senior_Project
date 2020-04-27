//The Pins to be sent to the H-Bridge////////////////////////////
#define motor_pin_1 3
#define motor_pin_2 5
#define motor_on 4
/////////////////////////////////////////////////////////////////
int incomingByte = 0;
int tmp = 0;
char a = 'f';
int b = 0;
int motor_flag = 1;
int motor_speed = 0;

////Initializing the MKR 1000 upon powering on////////////////////
void setup() {
  //Initialize pin to be output
  Serial1.begin(9600);
  pinMode(motor_pin_1, OUTPUT);
  pinMode(motor_pin_2, OUTPUT);
  pinMode(motor_on, OUTPUT);
  digitalWrite(motor_on, HIGH);
}
//////////////////////////////////////////////////////////////////

void loop() {
  //Switch motor and delay for 1 sec////////////////
  switchMotor();
  delay(1000);
  //////////////////////////////////////////////////


  /*If there is a serial signal, read the signal 
   * and change the pin valuses as needed*/
  if (Serial1.available() > 0) {
    while (incomingByte == tmp){
      tmp = Serial1.read();
    }
    incomingByte = tmp;
    

    if (incomingByte == 102) {
      a = 'f';
    }
    else if (incomingByte == 48) {
      a = 'o';
      b = 0;
    }
    else if (incomingByte == 49) {
      a = 'o';
      b = 1;
    }
    else if (incomingByte == 50) {
      a = 'o';
      b = 2;
    } else if (incomingByte == 51) {
      a = 'o';
      b = 3;
    } else if (incomingByte == 52) {
      a = 'o';
      b = 4;
    }
    else if(incomingByte == 97)
        {
            a = 'o';
            b = 'a';
        }
        else if(incomingByte == 98) 
        {
            a = 'o';
            b = 'b';
        }
        else if(incomingByte == 99) 
        {
            a = 'o';
            b = 'c';
        }
        else if(incomingByte == 100)
        {
            a = 'o';
            b = 'd';
        }
        else if(incomingByte == 101)
        {
            a = 'o';
            b = 'e';
        }
        else if(incomingByte == 103)
        {
            a = 'o';
            b = 'g';
        }
        else if(incomingByte == 104)
        {
            a = 'o';
            b = 'h';
        }
        else if(incomingByte == 105)
        {
            a = 'o';
            b = 'i';
        }
        else if(incomingByte == 106)
        {
            a = 'o';
            b = 'j';
        }
        else if(incomingByte == 107)
        {
            a = 'o';
            b = 'k';
        }
        else if(incomingByte == 108)
        {
            a = 'o';
            b = 'l';
        }
        else if(incomingByte == 109)
        {
            a = 'o';
            b = 'm';
        }
        else if(incomingByte == 110)
        {
            a = 'o';
            b = 'n';
        }
        else if(incomingByte == 112)
        {
            a = 'o';
            b = 'p';
        }
        else if(incomingByte == 113)
        {
            a = 'o';
            b = 'q';
        }
        else if(incomingByte == 114)
        {
            a = 'o';
            b = 'r';
        }
    else {
      Serial1.println("Waiting...");
    }
  }
  motor_control(a, b);
  delay(500);
}
//main function to change the pin values, 
//takes in the data that was given from the serial data 
//and uses that to change the values of the pins
void motor_control(char a, char b) {
  if (a == 'f') {
    analogWrite(motor_pin_1, 0);
    analogWrite(motor_pin_2, 0);
  }
  else {
    if (b == 0) {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 0);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 0);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 1) {

      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 112);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 112);
        analogWrite(motor_pin_1, 0);
      }

    }
    else if (b == 2) {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 153);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 153);
        analogWrite(motor_pin_1, 0);
      }
    } else if (b == 3) {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 192);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 192);
        analogWrite(motor_pin_1, 0);
      }
    } else if (b == 4) {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 240);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 240);
        analogWrite(motor_pin_1, 0);
      }
    } else if (b == 'a') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 80);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 80);
        analogWrite(motor_pin_1, 0);
      }
    } else if (b == 'b') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 88);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 88);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'c') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 96);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 96);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'd') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 104);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 104);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'e') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 121);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 121);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'g') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 129);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 129);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'h') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 137);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 137);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'i') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 145);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 145);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'j') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 161);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 161);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'k') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 170);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 170);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'l') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 178);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 178);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'm') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 184);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 184);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'n') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 201);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 201);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'p') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 209);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 209);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'q') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 218);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 218);
        analogWrite(motor_pin_1, 0);
      }
    }
    else if (b == 'r') {
      if (motor_flag == 1) {
        analogWrite(motor_pin_1, 229);
        analogWrite(motor_pin_2, 0);
      } else if (motor_flag == 2) {
        analogWrite(motor_pin_2, 229);
        analogWrite(motor_pin_1, 0);
      }
    }
  }
}
//this function switches which motor_flag is on,
//which determines which pin gets a motor value
void switchMotor() {
 
  if (motor_flag == 1) {
    motor_flag = 2;
  } else {
    motor_flag = 1;
  }
}
