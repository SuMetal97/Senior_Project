//Lucca Cecilio Salgueiro U12906672
//Thiago Olabarriaga U63889515
//Tiffany Jackson U27894536
//Luis Perez U22803017


#include <string.h>
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#include<sys/types.h>
#include<sys/socket.h>
#include<netinet/in.h>
#include<netdb.h>
#include<pthread.h>
//#include<malloc.h>

#include<arpa/inet.h>

#define NTHREADS 5
#define PORTNUM  3750 /* the port number the server will listen to*/
#define DEFAULT_PROTOCOL 0  /*constant for default protocol*/


void *stuff (void * _args);


//Global Variables
char mtxC[4][4];
char selected[16];
int mtxI[4][4], x = 0, plyrs = 0, sc1 = 0, sc2 = 0;
pthread_mutex_t mutex;

//Structure for arguments passed to through pthread_create process
struct thread_args{
 int cli_port;
 int newsockfd;
 };

//Other Functions for processing
void server_display();
void create_matrices();
int update_matrix(char buffer[], int sock);


int main( int argc, char *argv[] ) {
   int sockfd, newsockfd, portno, clilen;
   char buffer[256];
   struct sockaddr_in serv_addr, cli_addr;
   int status, i = 0;
    pthread_t ptid[NTHREADS];
   
    //Creation thread_args pointer args
    struct thread_args *args = malloc(sizeof(struct thread_args));

   
   /* First call to socket() function */
   sockfd = socket(AF_INET, SOCK_STREAM,DEFAULT_PROTOCOL );

   
   if (sockfd < 0) {
      perror("ERROR opening socket");
      exit(1);
   }
    
    
   
   /* Initialize socket structure */
   bzero((char *) &serv_addr, sizeof(serv_addr));
   portno = PORTNUM;
   
   serv_addr.sin_family = AF_INET;
   serv_addr.sin_addr.s_addr = INADDR_ANY;
   serv_addr.sin_port = htons(portno);
   
   /* Now bind the host address using bind() call.*/

   status =  bind(sockfd, (struct sockaddr *) &serv_addr, sizeof	(serv_addr)); 

   if (status < 0) {
      perror("ERROR on binding");
      exit(1);
   }
   
    
    //Creation of mutex lock
    pthread_mutex_init(&mutex, NULL);

   /* Now Server starts listening clients wanting to connect. No 	more than 5 clients allowed */
   
    if(listen(sockfd, 5) == 0){
        printf("[+]Listening...\n");
    }else{
        printf("ERROR!");
    }
    
   clilen = sizeof(cli_addr);
    
    //Create matrices that will be used if the game is played.
    //create_matrices();

   
   while (1) {
      newsockfd = accept(sockfd, (struct sockaddr *) &cli_addr, &clilen);
		
      if (newsockfd < 0) {
         perror("ERROR on accept");
         exit(1);
      }
       
       printf("Connection accepted from %s: %d\n", inet_ntoa(cli_addr.sin_addr), ntohs(cli_addr.sin_port));
       
       args->newsockfd = newsockfd;
       args->cli_port = ntohs(cli_addr.sin_port);
       
       if(pthread_create(&ptid[i++], NULL, stuff, (void *)args) != 0){
           printf("ERROR Creating Thread!");
       }
       //i++;
       
       if(i >= 2){
           create_matrices();
           i = 0;
           while(i < 2){
               pthread_join(ptid[i],NULL);
               i++;
           }
           i = 0;
       }
   } /* end of while */
   free(args);
}

void * stuff(void * _args){
    
    int status, sock, pts, cport, plyr1 = 0, plyr2 = 0;
    char buffer[256];
    bzero(buffer,256);
    
    //Casting of THREAD parameter to Integer
    struct thread_args *args = (struct thread_args *) _args;
    
    sock = args->newsockfd;
    cport = args->cli_port;
    
    //First read from socket if playing game
    status= read(sock,buffer,255);
    if (status < 0) {
        perror("ERROR reading from socket");
        exit(1);
    }
    
    //while(1){//While Loop #1
        
        //Print message received from client if they are playing the game
        printf("Client Message from Port [%d]: %s\n", cport, buffer);
        
        if(strcmp(buffer,"y\n") == 0){
            //pthread_mutex_lock(&mutex);
            //Keeps Track Of The Number Of Players
            plyrs++;
            
            //Write Back Player Status
            if(plyrs == 1){
                status = write(sock, "You Are Player 1!", 20);
                plyr1 = cport;
            }
            else if(plyrs == 2){
                status = write(sock, "You Are Player 2!", 20);
                plyr2 = cport;
                //create_matrices();
                server_display(); //Display Game Board and Values on Server Screen
            }
            //pthread_mutex_unlock(&mutex);
        }
        else{
           printf("Client From Port [%d] Disconnected.\n", cport);
           close(sock);
           pthread_exit(NULL);
        }
        
        //Mainly for first player. Wait for Plyr 2
        while(plyrs < 2){
            //Do Nothing. Wait On Player 2
        }
        
        
        while(plyrs >= 2){//While Loop #2
          //pthread_mutex_lock(&mutex);
           //Send Game Board Data back to Clients
           //printf("Whats being written back? - %s\n", &mtxC);
           status= write(sock,&mtxC, 16);
           if(status < 0){
               perror("ERROR Writing To Socket:");
               exit(1);
           }
          // pthread_mutex_unlock(&mutex);
           while(x < 16){//While Loop #3
               
               //Read Client's Game Board Selection
               status= read(sock,buffer,255);
               
               //Print Client's Game Board Selection On Server Screen
               printf("Client Message from Port [%d]: %s\n", cport, buffer);
               
               pthread_mutex_lock(&mutex);
               if(x < 15){
                   
                   //Update Char Matrix and Print On Server Screen
                   
                   pts = update_matrix(buffer, sock);
                   
                   
                   if(pts == 0){
                       
                       //Send Updated Game Board Data Back To Client
                       status= write(sock,&mtxC, 16);
                       if(status < 0){
                           perror("ERROR Writing To Socket:");
                           exit(1);
                       }
                   }else{
                       
                       //Print Value Behind Client Selection
                       printf("Location:%c | Value:%d\n", buffer[0], pts);
                       
                       if(plyr1 == cport){
                           sc1 = sc1 + pts;
                       }
                       
                       if(plyr2 == cport){
                           sc2 = sc2 + pts;
                       }
                       
                       //Print Score Status On Server Screen
                       printf("Current Score: Plyr1: %d | Plyr2: %d\n", sc1, sc2);
                       
                       //Send Updated Game Board Data Back To Client
                       status= write(sock,&mtxC, 16);
                       if(status < 0){
                           perror("ERROR Writing To Socket:");
                           exit(1);
                       }
                       //printf("x Equals...%d\n", x);
                   } //End of Pts IF Statement
               }else if(x==15){
                   
                   //Update Char Matrix and Print On Server Screen
                   
                   pts = update_matrix(buffer, sock);
                   
                   
                   if(pts == 0){
                       
                       //Send Updated Game Board Data Back To Client
                       status= write(sock,&mtxC, 16);
                       if(status < 0){
                           perror("ERROR Writing To Socket:");
                           exit(1);
                       }
                   }else{
                       
                       //Print Value Behind Client Selection
                       printf("Location:%c | Value:%d\n", buffer[0], pts);
                       
                       if(plyr1 == cport){
                           sc1 = sc1 + pts;
                       }
                       
                       if(plyr2 == cport){
                           sc2 = sc2 + pts;
                       }
                       
                       //Print Score Status On Server Screen
                       printf("Current Score: Plyr1: %d | Plyr2: %d\n", sc1, sc2);
                       //printf("x Equals...%d\n", x);
                   }
               }
               
               if(x > 15){
               
               //Finalize Scores By Comparison....
               if(sc1 > sc2){
                   
                   //Send Message To Both Clients That the Game Is OVER!
                   status = write(sock, "Player 1 Wins This Round. Play Again(y/n): ", 50);
                   //printf("x Equals...%d\n", x);
                   }
                   else if(sc1 < sc2){
                       
                       //Send Message To Both Clients That the Game Is OVER!
                       status = write(sock, "Player 2 Wins This Round. Play Again(y/n): ", 50);
                       //printf("x Equals...%d\n", x);
                   }
                   
                   //Reset Number Of Players
                   plyrs = 0;
               }
               pthread_mutex_unlock(&mutex);
           }//End of While Loop #3
           
           //Read Client Response on Game Status
           status= read(sock,buffer,255);
           if (status < 0) {
               perror("ERROR reading from socket");
               exit(1);
           }
           
           if(strcmp(buffer, "y\n") == 0){
               
               plyrs++;
               //create_matrices(); //WARNING matrices will be created again by both clients
               
               //Mainly for first player. Wait for Plyr 2
        while(plyrs < 2){
            //Do Nothing. Wait On Player 2
        }
               if(plyrs == 2){
                   x = 0;
                   sc1 = 0;
                   sc2 = 0;
                   create_matrices();
                   server_display(); //Display Game Board and Values on Server Screen
               }
           }else{
               //plyrs--;
               printf("Client From Port [%d] Disconnected\n", cport);
               break;
           }
        }//End of While Loop #2
    //}//End Of While Loop #1
    
    //free(args);
    close(sock);
    pthread_exit(NULL);   
}

void create_matrices(){
    
    char ltr = 'a';
    int a, b;
    
    //creating matrix with chars
    for(a = 0; a < 4; a++){
        for(b = 0; b < 4; b++){
            mtxC[a][b] = ltr;
            ltr++;
        }
    }
    
    //Creating matrix with ints
    for(a = 0; a < 4; a++){
        for(b = 0; b < 4; b++){
            mtxI[a][b] = rand() % 100 + 1;
        }
    }
    
    //Initiazlize Selections Array to Zeros
    for(a = 0; a < 16; a++){
        selected[a] = ' ';
    }
    //selected[16] = "";
}

//Prints the matrices for the server
void server_display(){
    
    int a, b;
    
    //Printing matrix with Chars
    for(a = 0; a < 4; a++){
        for(b = 0; b < 4; b++){
            printf("%c ", mtxC[a][b]);
        }
        printf("\n");
    }
    
    printf("\n");
    
    for(a = 0; a < 4; a++){
        for(b = 0; b < 4; b++){
            printf("%d ", mtxI[a][b]);
        }
        printf("\n");
    }
}

//Updates Character Matrix With '-' From Client's Selection
int update_matrix(char buffer[], int sock){
    
    int a, b, val, status;
    
    //Search Selected Array
    for(a = 0; a < 16; a++){
        
        //If Selection Already In Array, Send Message Back To Client
        if(selected[a] == buffer[0]){
            status= write(sock,"Location Has Already Been Selected", 34);
            if(status < 0){
                perror("ERROR Writing To Socket:");
                exit(1);
            }
            val = 0;
            break;
        }
    }
    
    if(a == 16){
        
        //For Loop to Search, Then Update Array Location
        for(a = 0; a < 4; a++){
            for(b = 0; b < 4; b++){
                if(mtxC[a][b] == buffer[0]) {
                    
                    mtxC[a][b] = '-';
                    
                    //Store selection in Selected Array
                    selected[x++] = buffer[0];
                    
                    //Store Number Value in Val Which Will Be Returned
                    val = mtxI[a][b];
                    
                    break;
                }
            }
        }
        
        //Displays Updated Matrix After Client Selection
        for(a = 0; a < 4; a++){
            for(b = 0; b < 4; b++){
                printf("%c ", mtxC[a][b]);
            }
            printf("\n");
        }
    }
    return val;
}
/*void *doprocessing (void * arg) {
   int status;
   char buffer[256];
 
    int sock = *((int *) arg);
 
    bzero(buffer,256);
  status= read(sock,buffer,255);
 
   if (status < 0) {
      perror("ERROR reading from socket");
      exit(1);
   }
   
   printf("Here is the message: %s\n",buffer);
  status= write(sock,"I got your message",18);
   
   if (status < 0) {
      perror("ERROR writing to socket");
      exit(1);
   }
    close(sock);
    pthread_exit(NULL);
	
}*/

