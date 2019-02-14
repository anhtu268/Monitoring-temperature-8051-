														#include <REGX52.H>
#include <stdio.h>

#define DQ P3_7
#define led P2_2
#define chuong P2_3
int check_ok,delay_ok,check_data,ghi_chuong,ghi_temp,alert,max_temp,temp,k,check_send;
unsigned char a;

//-------------------------------------------------------------
void delay_ms(int t)
{
	unsigned int i;
	unsigned char j;
	for(i=0;i<t;i++)
		for(j=0;j<140;j++);
}
void delay_us(int us)
{
	int i;
	for (i=0; i<us; i++);
}

void delay(int time)
{
	while(time--);
}
//----------------------------------------
// Reset DS1820
//----------------------------------------
bit ResetDS1820(void)
{
	bit presence;
	DQ = 0; 		//pull DQ line low
	delay_us(29); 	// leave it low for about 490us
	DQ = 1; 		// allow line to return high
	delay_us(3); 	// wait for presence 55 uS
	presence = DQ; 	// get presence signal
	delay_us(25); 	// wait for end of timeslot 316 uS 
	return(presence); // presence signal returned
} 	// 0=presence, 1 = no part

//-----------------------------------------
// Read one bit from DS1820
//-----------------------------------------
unsigned char ReadBit(void)
{
	unsigned char i;
	DQ = 0; 	// pull DQ low to start timeslot
	DQ=1;
	for (i=0; i<3; i++); // delay 17 us from start of timeslot
	return(DQ); // return value of DQ line
}

//-----------------------------------------
// Write one bit to DS1820
//-----------------------------------------
void WriteBit(char bitval)
{
	DQ=0;	
	if(bitval==1)
		DQ = 1;
	delay_us(5); 			// delay about 39 uS
	DQ = 1;
}

//-----------------------------------------
// Read 1 byte from DS1820
//-----------------------------------------
unsigned char ReadByte(void)
{
	unsigned char i;
	unsigned char value = 0;
	for (i=0;i<8;i++)
	{
		if(ReadBit()) value|=0x01<<i;
		delay_us(6); 
	}
	return(value);
}
//-----------------------------------------
// Write 1 byte
//-----------------------------------------
void WriteByte(char val)
{
	unsigned char i;
	unsigned char temp;
	for (i=0; i<8; i++) // writes byte, one bit at a time
	{	       
		temp = val>> i;
		temp &=0x01;
		WriteBit(temp);
	}
	delay_us(5);
}

//-----------------------------------------
// Read temperature
//-----------------------------------------
void ReadTemp(void)
{
	EA=0;
	ResetDS1820();
    WriteByte(0xcc);  // skip ROM
    WriteByte(0x44);  // perform temperatur conversion
    delay_ms(700);	
    ResetDS1820();
    WriteByte(0xcc);  // skip ROM
    WriteByte(0xbe);  // read the result
    temp = ReadByte();
	temp = (ReadByte() << 8)+temp;
	temp = temp >> 4;
	EA=1;
}
void nhandulieu() interrupt 4
{
	if(RI)
	{
		a =SBUF;
		if(ghi_chuong)					// Ghi lai trang thai chuong
		{
			alert = a-48;
		 	ghi_chuong = 0;
		}
		else ghi_chuong = 0;
		if(ghi_temp==1&&k==0)			// Ghi lai max temp
		{
			max_temp = (a-48)*10;
			k=1;
		}
		else if(ghi_temp==1&&k==1)
		{
			max_temp = max_temp + (a -48);
			ghi_temp = 0;
		}
		else
		{
			k=0;
			ghi_temp=0;
		} 
		if(a=='T'&&check_data == 1) ghi_temp = 1;
		if(a=='C'&&check_data == 1) ghi_chuong = 1;
		if(a=='O') check_ok = 1;				// Check OK
		else if(a=='K'&&check_ok == 1) delay_ok = 1;
		else if(a!='K'&&check_ok == 1) check_ok = 0;
		else check_ok = 0;
		if(a==63) check_data = 1;	 			// Check temp max va chuong	  ?=63
		else check_data = 0;
		if(a=='>') check_send = 1;
		else check_send = 0;
		if(temp>max_temp&&alert==1) chuong = 1;
		else if(temp<=max_temp||alert==0) chuong = 0;
	}
	RI=0;
}
int check()
{
	int i=400000;
	delay_ok=0;
	while(!delay_ok)
	{
		i--;
		if(!i) break;
	}
	if(i) return 1;
	else return 0;
}
int check_s()
{
	int i=40000000;
	check_send=0;
	while(!check_send)
	{
		i--;
		if(!i) break;
	}
	if(i) return 1;
	else return 0;
}
//*********Ham CHinh*************//
void main()
{
	SM0=0;SM1=1;
	TMOD=0x20;
	TH1=0xFD;
	TR1=1;
	TI=1;
	REN=1;
	IE=0x90;	// Cho phep ngat truyen thong noi tiep
	delay_ok=0;
	chuong = 0;
	alert = 1;
	max_temp = 50;
	do ReadTemp();
	while(temp!=85);
	delay(1000);
	start: led =1 ; delay(1000); led = 0;delay(1000); led =1; delay(1000);
	printf("AT+RST\r\n");
	led = 0;			  					// reset module
	if(!check()) goto start;
	led	= 1;
	delay(1000);
	printf("AT+CWJAP=\"ATT409\",\"5654665991\"\r\n");	  //nhap pass wifi
	led = 0;
	if(!check()) goto start;
	led = 1;
	delay(1000);
	while(1)
	{
		ReadTemp();
		printf("AT+CIPSTART=\"TCP\",\"192.168.0.111\",80\r\n");
		led = 0;			  			
		if(!check()) goto start;
		led	= 1;
		delay(1000);
		printf("AT+CIPSEND=52\r\n");
		led = 0;			  			
		if(!check_s()) goto start;
		led	= 1;
		delay(1000);
		printf("GET /g.php?t=%d HTTP/1.1\r\nHost: 192.168.0.111\r\n\r\n",temp);
		led = 0;			  			
		if(!check()) goto start;
		led	= 1;
		delay(1000);
	}
}



