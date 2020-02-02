//Lucca Cecilio Salgueiro U12906672
//Thiago Olabarriaga U63889515
//Tiffany Jackson U27894536
//Luis Perez U22803017

/* this program shows how to create sockets for a client.
it also shows how the client connects to a server socket.
and sends a message to it. the server must already be running
on a machine. The name of this machine must be entered in the function gethostbyname
in the code below. The port number where the server is listening is specified in
PORTNUM. This port number must also be specified in the server code.*/

#include<stdio.h>
#include<unistd.h>
#include<stdlib.h>
#include<string.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<netinet/in.h>
#include<netdb.h>

#define PORTNUM  3750 /* the port number that the server is listening to*/
#define DEFAULT_PROTOCOL 0  /*constant for default protocol*/

void client_display(char buffer[]);

void main(){
    
   int  port;
   int  socketid;      /*will hold the id of the socket created*/
   int  status;        /* error status holder*/
   char buffer[256];   /* the message buffer*/
   struct sockaddr_in serv_addr;
   int a, b, x = 0;

   struct hostent *server;

   /* this creates the socket*/
   socketid = socket (AF_INET, SOCK_STREAM, DEFAULT_PROTOCOL);
   if (socketid < 0){
       
      printf( "error in creating client socket\n"); 
      exit (-1);
    }

    printf("Created Client socket successfully\n");

   /* Before connecting the socket we need to set up the right values in the
      different fields of the structure server_addr
      you can check the definition of this structure on your own */
   
    server = gethostbyname("osnode05");

    if(server == NULL){
        
      printf("Error trying to identify the machine where the server is running\n");
      exit(0);
    }

    port = PORTNUM;

    /* This function is used to initialize the socket structures with null values. */
    bzero((char *) &serv_addr, sizeof(serv_addr));

   serv_addr.sin_family = AF_INET;
   bcopy((char *)server->h_addr,(char *)&serv_addr.sin_addr.s_addr,server->h_length);
   serv_addr.sin_port = htons(port);
   
   /* connecting the client socket to the server socket */

   status = connect(socketid, (struct sockaddr *) &serv_addr, sizeof(serv_addr));
   if (status < 0)
    {
      printf( " error in connecting client socket with server	\n");
      exit(-1);
    }

   printf("Connected client socket to the server socket \n");

   /* now lets send a message to the server. the message will be
     whatever the user wants to write to the server.*/
  
    printf("Ready to Play?(y/n):");
    bzero(buffer,256);
    fgets(buffer,255,stdin);
    
    //Write back to server if playing game
    status = write(socketid, buffer, strlen(buffer));
    if (status < 0){
        printf("ERROR while sending client message to server\n");
    }
    
    //bzero(buffer, 256);
    
    //Reads the buffer which should contain Player Status
    status = read(socketid, buffer, 255);
    
    printf("%s\n", buffer);
    //Zero out buffer after player status read
    //bzero(buffer, 256);
    
    while (1){
        
        //Reads the buffer
        status = read(socketid, buffer, 255);
        
        //Display Gameboard on Client screen
        //client_display(buffer);
        
        //while(1){
            
            //Reads the buffer which should contain Player Status
            //status = read(socketid, buffer, 255);
    
            if(status == 16){
                //printf("What is in this buffer? - %s\n", buffer);
                client_display(buffer);
                //Client makes slection from Game Board
                printf("Make a selection between a - p: ");
                
                //Clear out buffer
                bzero(buffer, 256);
                
                //Store user input on Client buffer
                fgets(buffer, 255, stdin);
                
                //Write location to Server
                status = write(socketid, buffer, strlen(buffer));
                if (status < 0){
                    printf("ERROR while sending client message to server\n");
                    
                    //bzero(buffer, 256);
                }
            }else if(status == 34){
                
                //Message if Location Already Selected
                printf("%s\n", buffer);
                
                bzero(buffer, 256);
                
            }else if(status == 50){
                printf("%s", buffer);
            
                bzero(buffer, 256);
                
                fgets(buffer, 255, stdin);
                
                if(strcmp(buffer, "y\n") == 0){
                    
                    status = write(socketid, buffer, 255);
                    
                }else{
                    
                    printf("Client Exiting Game.\n");
                    break;
                }
            }
        }//End of While
    
            //Should Read Updated Gameboard
            /*status = read(socketid, buffer, 255);
            if (status < 0) {
                perror("ERROR while reading message from server\n");
                exit(1);
            }
            
            client_display(buffer);*/
        //}//End of While #2
        
        /*if(status == 50){
            
            printf("%s", buffer);
            
            bzero(buffer, 256);
            
            fgets(buffer, 255, stdin);
            
            if(strcmp(buffer, "y\n") == 0){
                
                status = write(socketid, buffer, 255);
                
            }else{
                
                printf("Client Exiting Game.\n");
                break;
            }
        }*/
    //}//End of While #1
    
    //Close Client Socket
    close(socketid);
    
        //Annoucement For Not Enough Players
        /*if(status == 75){
            
            printf("%s", buffer);
            
            bzero(buffer, 256);
            
            fgets(buffer, 255, stdin);
            
            if(strcmp(buffer, "w\n") == 0){
                
                status = write(socketid, buffer, 255);
                
            }
        }*/
        
    
    
    
} 
     
void client_display(char buffer[]){
    
    int a, b, i = 0;
    
    for(a = 0; a < 4; a++){
        for(b = 0; b < 4; b++) {
            printf("%c ", buffer[i++]);
        }
        printf("\n");
    }
    
}
