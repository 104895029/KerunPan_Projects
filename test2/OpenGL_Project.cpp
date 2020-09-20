/*
	COMP-3520 A4
	Kerun Pan
	SID: 104895029

*/

/* Change these to what will work for you */
#pragma once
#define _CRT_SECURE_NO_WARNINGS

#include <tchar.h>
#include <stdio.h>
#include <stdlib.h>
#include <GL/glut.h>
#include <time.h>
#include <math.h>
#include <fstream>
#include <iostream>
#include <vector>
#include <string>
#include <sstream>

using namespace std;

/* Global Variables */


#define initial_W 500
#define initial_H 500
#define room 7


int W = initial_W;
int H = initial_H;

struct coords1 {
	int x;     //X-axis rotation
	int y;
	int z;//Y-axis rotation
	int speed; //rotation speed
} cube = { 0, 0, 0 ,5 }, //where make movements here
reset = { 0, 0, 0, 5 }; //a copy of the original coordinates


struct coords2 {
	int x;            //initial position (top left corner)
	int y;
	int z;
	int s;            //width in pixels
	int speed;        //object speed
	float c1, c2, c3; //RGB colour
} carObject = { -100, -100, 0, 10, 1, 0, 0, 0 }; //where make movements here

// Position of the Camera
double eyeX = 0, eyeY = 0.2, eyeZ = 0;

// The coordinate to place in the center of the screen
int centerX = 12, centerY = 15, centerZ = -12;

// The up direction
int upX = 0, upY = 1, upZ = 0;


void reshape(int, int);
void drawFrame();
void ClickMousePos(int, int, int, int);
void processNormalKeys(unsigned char key, int x, int y);
void processSpecialKeys(int key, int x, int y);
void MenuBar(int);
void DrawFences();
void DrawGrass();
void DrawXRoad();
void DrawTree();
void DrawCar();
void DrawPedestrian();

//global house primitives
float house_data[2000];

//draw car
double car = 0;
double leftRightMove = 0;


int main(int argc, char** argv) {

	ifstream inFile;

	inFile.open("HouseData.txt");
	if (inFile.fail())
	{
		cout << "Cannot open HouseData.txt" << endl;
	}

	char content[2000];

	if (!inFile) {
		cout << "Unable to open file";
		exit(1); // terminate with error
	}

	inFile.read(content, 2000);

	inFile.close();
	//cout << "content = " << content << endl;



	int i = 0;
	char* pt;
	pt = strtok(content, ",");
	while (pt != NULL && i < 2000) {
		house_data[i] = atof(pt);
		pt = strtok(NULL, ",");

		//	cout << "i = " << i << "house_data = " << house_data[i] << endl;
		i++;
	}



	time_t t;
	srand((unsigned)time(&t));

	glutInit(&argc, argv);
	glutInitDisplayMode(GLUT_DOUBLE | GLUT_RGB | GLUT_DEPTH);
	glutInitWindowSize(W, H);
	glutInitWindowPosition(100, 150);
	glutCreateWindow(argv[0]);

	glutDisplayFunc(drawFrame);
	glutReshapeFunc(reshape);
	glutMouseFunc(&ClickMousePos);
	glutKeyboardFunc(&processNormalKeys);
	glutSpecialFunc(&processSpecialKeys);

	//  Enable Z-buffer depth test
	glEnable(GL_DEPTH_TEST);


	glutCreateMenu(MenuBar);
	glutAddMenuEntry("Start the car", 1);
	glutAddMenuEntry("Stop the car", 2);
	glutAddMenuEntry("Speed-up the car", 3);
	glutAddMenuEntry("Slow-down the car", 4);
	glutAddMenuEntry("Camera viewing the whole scene from above the street", 5);
	glutAddMenuEntry("normal Camera viewing", 6);


	glutAttachMenu(GLUT_RIGHT_BUTTON);

	glClearColor(1.0, 1.0, 1.0, 0.0);
	glColor3f(0, 0, 0);
	glPointSize(1.0);

	glMatrixMode(GL_PROJECTION);
	glLoadIdentity();
	glMatrixMode(GL_MODELVIEW);
	glLoadIdentity();

	gluLookAt(eyeX, eyeY, eyeZ, centerX, centerY, centerZ, upX, upY, upZ);

	glutMainLoop();

	return 0;
}





/**
	This function handles the window resize event.
**/
void reshape(int w, int h)
{
	W = w;
	H = h;
	glViewport(0, 0, (GLsizei)w, (GLsizei)h);
	glMatrixMode(GL_PROJECTION);
	glLoadIdentity();
	glMatrixMode(GL_MODELVIEW);
	glLoadIdentity();
	gluLookAt(eyeX, eyeY, eyeZ, centerX, centerY, centerZ, upX, upY, upZ);

}

float _angle = 45.0f;
float _cameraAngle = 0.0f;


/**
	This is our only draw function.
**/
void drawCubeModel() {

	// main rec 
	glClear(GL_COLOR_BUFFER_BIT);

	glColor3f(house_data[0], house_data[1], house_data[2]);
	glBegin(GL_POLYGON);
	for (int j = 3; j < 14; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	// main rec 

	/*
		glClear(GL_COLOR_BUFFER_BIT);
	glColor3f(house_data[0], house_data[1], house_data[2]);
	glBegin(GL_POLYGON);
	glVertex3f(house_data[3], house_data[4], house_data[5]);
	glVertex3f(house_data[6], house_data[7], house_data[8]);
	glVertex3f(house_data[9], house_data[10], house_data[11]);
	glVertex3f(house_data[12], house_data[13], house_data[14]);
	glEnd();
	*/



	// bot rec 
	/*
		glColor3f(house_data[15], house_data[16], house_data[17]);
	glBegin(GL_POLYGON);
	glVertex3f(house_data[18], house_data[19], house_data[20]);
	glVertex3f(house_data[21], house_data[22], house_data[23]);
	glVertex3f(house_data[24], house_data[25], house_data[26]);
	glVertex3f(house_data[27], house_data[28], house_data[29]);
	glEnd();
	*/
	// bot rec 
	glColor3f(house_data[15], house_data[16], house_data[17]);
	glBegin(GL_POLYGON);
	for (int j = 18; j < 29; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();


	//back rec
	/*
		glColor3f(house_data[30], house_data[31], house_data[32]);
	glBegin(GL_POLYGON);
	glVertex3f(house_data[33], house_data[34], house_data[35]);
	glVertex3f(house_data[36], house_data[37], house_data[38]);
	glVertex3f(house_data[39], house_data[40], house_data[41]);
	glVertex3f(house_data[42], house_data[43], house_data[44]);
	glEnd();
	*/

	glColor3f(house_data[30], house_data[31], house_data[32]);
	glBegin(GL_POLYGON);
	for (int j = 33; j < 44; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();


	// left rec 
	/*
		glColor3f(house_data[45], house_data[46], house_data[47]);
	glBegin(GL_POLYGON);
	glVertex3f(house_data[48], house_data[49], house_data[50]);
	glVertex3f(house_data[51], house_data[52], house_data[53]);
	glVertex3f(house_data[54], house_data[55], house_data[56]);
	glVertex3f(house_data[57], house_data[58], house_data[59]);
	glEnd();
	*/

	glColor3f(house_data[45], house_data[46], house_data[47]);
	glBegin(GL_POLYGON);
	for (int j = 48; j < 59; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	//left rec window

	glColor3f(house_data[60], house_data[61], house_data[62]);
	glBegin(GL_POLYGON);
	for (int j = 63; j < 74; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	/* right rec */
	glColor3f(house_data[75], house_data[76], house_data[77]);
	glBegin(GL_POLYGON);
	for (int j = 78; j < 89; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();




	//right rec window
	glColor3f(house_data[90], house_data[91], house_data[92]);
	glBegin(GL_POLYGON);
	for (int j = 93; j < 104; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();


	/* left tri */


	glColor3f(house_data[105], house_data[106], house_data[107]);
	glBegin(GL_POLYGON);
	for (int j = 108; j < 116; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	/* right tri */
	glColor3f(house_data[117], house_data[118], house_data[119]);
	glBegin(GL_POLYGON);
	for (int j = 120; j < 128; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	/* roof */
	glColor3f(house_data[129], house_data[130], house_data[131]);
	glBegin(GL_POLYGON);
	for (int j = 132; j < 143; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	/*back roof */
	glColor3f(house_data[144], house_data[145], house_data[146]);
	glBegin(GL_POLYGON);
	for (int j = 147; j < 158; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	/* door */
	glColor3f(house_data[159], house_data[160], house_data[161]);
	glBegin(GL_POLYGON);
	for (int j = 162; j < 173; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();


	//Glass color 
	//168 204 215
	//0.65 0.79 0.84
	/* window 1 */
	glColor3f(house_data[174], house_data[175], house_data[176]);
	glBegin(GL_POLYGON);
	for (int j = 177; j < 188; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	/* window 2 */
	glColor3f(house_data[189], house_data[190], house_data[191]);
	glBegin(GL_POLYGON);
	for (int j = 192; j < 203; j += 3)
	{
		glVertex3f(house_data[j], house_data[j + 1], house_data[j + 2]);

	}
	glEnd();

	//-----------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	/*backyard*/


	DrawFences();
	DrawGrass();
	DrawXRoad();
	DrawTree();
	DrawCar();


}


void DrawFences()
{
	/* left fence*/
	glColor3f(0.39, 0.26, 0.13);//brown color
	glBegin(GL_POLYGON);
	glVertex3f(0.1, 0.13, 1);
	glVertex3f(0.1, 0.13, 0.0);
	glVertex3f(0.1, 0.17, 0.0);
	glVertex3f(0.1, 0.17, 1);
	glEnd();

	for (int i = 0; i < 10; i++)
	{
		float m = 0.1;
		/* left fence 1 */
		glColor3f(0.71, 0.39, 0.11);//light brown color
		glBegin(GL_POLYGON);
		glVertex3f(0.1, 0.1, 1 - m * i);
		glVertex3f(0.1, 0.1, 0.95 - m * i);
		glVertex3f(0.1, 0.3, 0.95 - m * i);
		glVertex3f(0.1, 0.3, 1 - m * i);
		glEnd();
	}

	/*right fence*/
	glColor3f(0.39, 0.26, 0.13);//brown color
	glBegin(GL_POLYGON);
	glVertex3f(1, 0.13, 1);
	glVertex3f(1, 0.13, 0.0);
	glVertex3f(1, 0.17, 0.0);
	glVertex3f(1, 0.17, 1);
	glEnd();

	for (int i = 0; i < 10; i++)
	{
		float m = 0.1;
		/* right fence 1 */
		glColor3f(0.71, 0.39, 0.11);//light brown color
		glBegin(GL_POLYGON);
		glVertex3f(1, 0.1, 1 - m * i);
		glVertex3f(1, 0.1, 0.95 - m * i);
		glVertex3f(1, 0.3, 0.95 - m * i);
		glVertex3f(1, 0.3, 1 - m * i);
		glEnd();
	}


	/*back rec*/
	glColor3f(.5, .75, .35);
	glBegin(GL_POLYGON);
	glVertex3f(0.2, 0.1, 0.5);
	glVertex3f(0.9, 0.1, 0.5);
	glVertex3f(0.9, 0.575, 0.5);
	glVertex3f(0.2, 0.575, 0.5);
	glEnd();


	/*back fences*/
	glColor3f(0.39, 0.26, 0.13);//brown color
	glBegin(GL_POLYGON);
	glVertex3f(0.1, 0.13, 1);
	glVertex3f(1, 0.13, 1);
	glVertex3f(1, 0.17, 1);
	glVertex3f(0.1, 0.17, 1);
	glEnd();

	for (int i = 0; i < 10; i++)
	{
		float m = 0.1;
		/* right fence 1 */
		glColor3f(0.71, 0.39, 0.11);//light brown color
		glBegin(GL_POLYGON);
		glVertex3f(0.1 + m * i, 0.1, 1);
		glVertex3f(0.15 + m * i, 0.1, 1);
		glVertex3f(0.15 + m * i, 0.3, 1);
		glVertex3f(0.1 + m * i, 0.3, 1);
		glEnd();
	}


	glColor3f(0.49, 0.78, 0.31);//grass green
}

void DrawGrass()
{
	glColor3f(0.49, 0.78, 0.31);//grass green
	glBegin(GL_POLYGON);
	glVertex3f(0.1, 0.1, 0.5);
	glVertex3f(1, 0.1, 0.5);
	glVertex3f(1, 0.1, 1);
	glVertex3f(0.1, 0.1, 1);
	glEnd();
}

void DrawTree()
{
	//back trees
	for (int i = 0; i < 4; i++)
	{
		float m = -0.5;

		glColor3f(0.49 , 0.78, 0.31);//leaf green
		glBegin(GL_POLYGON);
		glVertex3f(-0.7 + m * i, 0.3, 0);
		glVertex3f(-0.3 + m * i, 0.3, 0);
		glVertex3f(-0.5 + m * i, 1, 0);
		glEnd();

		/* wood*/
		glColor3f(0.71, 0.39, 0.11);//light brown color
		glBegin(GL_POLYGON);
		glVertex3f(-0.55 + m * i, 0.1, 0);
		glVertex3f(-0.45 + m * i, 0.1, 0);
		glVertex3f(-0.45 + m * i, 0.5, 0);
		glVertex3f(-0.55 + m * i, 0.5, 0);
		glEnd();
	}

	//front trees
	for (int i = 0; i < 5; i++)
	{
		float m = 0.5;

		glColor3f(0.49, 0.78, 0.31);//leaf green
		glBegin(GL_POLYGON);
		glVertex3f(-0.7 + m * i, 0.3, -1);
		glVertex3f(-0.3 + m * i, 0.3, -1);
		glVertex3f(-0.5 + m * i, 1, -1);
		glEnd();

		/* wood*/
		glColor3f(0.71, 0.39, 0.11);//light brown color
		glBegin(GL_POLYGON);
		glVertex3f(-0.55 + m * i, 0.1, -1);
		glVertex3f(-0.45 + m * i, 0.1, -1);
		glVertex3f(-0.45 + m * i, 0.5, -1);
		glVertex3f(-0.55 + m * i, 0.5, -1);
		glEnd();
	}
}

void DrawCircle(float cx, float cy,float cz)
{
	glBegin(GL_POLYGON);
	glColor3f(1, 0, 0);
	for (int ii = 0; ii < 100; ii++)
	{
		float theta = 2.0f * 3.1415926f * float(ii) / float(100);//get the current angle

		float x = 0.05 * cosf(theta);//calculate the x component
		float y = 0.05 * sinf(theta);//calculate the y component

		glVertex3f(x + cx, y + cy, cz);//output vertex

	}
	glEnd();
}


static double carXSpeedPos = 0;
static double changeSpeed = 0;

void DrawCar()
{
	carXSpeedPos = carXSpeedPos + changeSpeed;

	//std::cout <<" carXSpeedPos :" << carXSpeedPos << std::endl;
	//car bot
	glColor3f(0.77, 0.67, 0.45);
	glBegin(GL_POLYGON);
	glVertex3f(0.7- carXSpeedPos, 0.2, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.2, -0.6);
	glVertex3f(0.7 - carXSpeedPos, 0.2, -0.6);
	glEnd();
	
	//car topping
	glColor3f(0.75, 0.75, 0.25);
	glBegin(GL_POLYGON);
	glVertex3f(0.7 - carXSpeedPos, 0.4, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.4, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.4, -0.6);
	glVertex3f(0.7 - carXSpeedPos, 0.4, -0.6);
	glEnd();

	//car front
	glColor3f(1, 0.49, 0);
	glBegin(GL_POLYGON);
	glVertex3f(0.7 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(0.7 - carXSpeedPos, 0.4, -0.3);
	glVertex3f(0.7 - carXSpeedPos, 0.4, -0.6);
	glVertex3f(0.7 - carXSpeedPos, 0.2, -0.6);
	glEnd();

	//car back
	glColor3f(1, 0.49, 0);
	glBegin(GL_POLYGON);
	glVertex3f(1 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.4, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.4, -0.6);
	glVertex3f(1 - carXSpeedPos, 0.2, -0.6);
	glEnd();

	//car left 
	glColor3f(0.15, 0.2, 0.3);
	glBegin(GL_POLYGON);
	glVertex3f(0.7 - carXSpeedPos, 0.2, -0.6);
	glVertex3f(1 - carXSpeedPos, 0.2, -0.6);
	glVertex3f(1 - carXSpeedPos, 0.4, -0.6);
	glVertex3f(0.7 - carXSpeedPos, 0.4, -0.6);
	glEnd();

	//car right
	glColor3f(0.15, 0.2, 0.3);
	glBegin(GL_POLYGON);
	glVertex3f(0.7 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1 - carXSpeedPos, 0.4, -0.3);
	glVertex3f(0.7 - carXSpeedPos, 0.4, -0.3);
	glEnd();


	//car bot2
	glColor3f(0.77, 0.67, 0.45);
	glBegin(GL_POLYGON);
	glVertex3f(0.6 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.2, -0.6);
	glVertex3f(0.6 - carXSpeedPos, 0.2, -0.6);
	glEnd();

	//car topping2
	glColor3f(0.65, 0.79, 0.84);
	glBegin(GL_POLYGON);
	glVertex3f(0.6 - carXSpeedPos, 0.3, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.3, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.3, -0.6);
	glVertex3f(0.6 - carXSpeedPos, 0.3, -0.6);
	glEnd();

	//car front2
	glColor3f(1, 0.49, 0);
	glBegin(GL_POLYGON);
	glVertex3f(0.6 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(0.6 - carXSpeedPos, 0.3, -0.3);
	glVertex3f(0.6 - carXSpeedPos, 0.3, -0.6);
	glVertex3f(0.6 - carXSpeedPos, 0.2, -0.6);
	glEnd();

	//car back2
	glColor3f(1, 0.49, 0);
	glBegin(GL_POLYGON);
	glVertex3f(1.1 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.3, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.3, -0.6);
	glVertex3f(1.1 - carXSpeedPos, 0.2, -0.6);
	glEnd();

	//car left2 
	glColor3f(0.15, 0.2, 0.3);
	glBegin(GL_POLYGON);
	glVertex3f(0.6 - carXSpeedPos, 0.2, -0.6);
	glVertex3f(1.1 - carXSpeedPos, 0.2, -0.6);
	glVertex3f(1.1 - carXSpeedPos, 0.3, -0.6);
	glVertex3f(0.6 - carXSpeedPos, 0.3, -0.6);
	glEnd();

	//car right2
	glColor3f(0.15, 0.2, 0.3);
	glBegin(GL_POLYGON);
	glVertex3f(0.6 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.2, -0.3);
	glVertex3f(1.1 - carXSpeedPos, 0.3, -0.3);
	glVertex3f(0.6 - carXSpeedPos, 0.3, -0.3);
	glEnd();


	//left front wheel
	DrawCircle(0.7 - carXSpeedPos, 0.2, -0.61);
	//right front wheel
	DrawCircle(0.7 - carXSpeedPos, 0.2, -0.29);
	//left back wheel
	DrawCircle(1 - carXSpeedPos, 0.2, -0.61);
	//right back wheel
	DrawCircle(1 - carXSpeedPos, 0.2, -0.29);

	/*
	glColor3f(0.77, 0.67, 0.45);
	glBegin(GL_POLYGON);
	glVertex3f(-3, 0.1, -0.4);
	glVertex3f(2, 0.1, -0.4);
	glVertex3f(2, 0.1, -0.5);
	glVertex3f(-3, 0.1, -0.5);
	glEnd();

		//prey border red
	glLineWidth(2.0);
	glColor3f(1.0f, 0.0f, 0.0f);

	glPolygonMode(GL_FRONT_AND_BACK, GL_LINE);
	glBegin(GL_POLYGON);

	glVertex2i(0, 0);
	glVertex2i(1, 0);
	glVertex2i(1, 1);
	glVertex2i(0, 1);
	glEnd();

	//prey fill green
	glColor3f(0.0f, 1.0f, 0.0f);

	glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);
	glBegin(GL_POLYGON);

	glVertex2i(0, 0);
	glVertex2i(1, 0);
	glVertex2i(1, 1);
	glVertex2i(0, 1);
	glEnd();
	*/

}



void DrawXRoad()
{
	glColor3f(0, 0, 0);//black
	glBegin(GL_POLYGON);
	glVertex3f(-3, 0.1, -0.2);
	glVertex3f(2, 0.1, -0.2);
	glVertex3f(2, 0.1, -0.7);
	glVertex3f(-3, 0.1, -0.7);
	glEnd();

	for (int i = 0; i < 20; i++)
	{
		float m = 0.3;
		/* right fence 1 */
		glColor3f(1, 1, 1);//white

		glBegin(GL_POLYGON);
		glVertex3f(-3 + m * i, 0.1, -0.4);
		glVertex3f(-2.8 + m * i, 0.1, -0.4);
		glVertex3f(-2.8 + m * i, 0.1, -0.5);
		glVertex3f(-3 + m * i, 0.1, -0.5);
		glEnd();

	}
}

static double PZXSpeedPos = -0.0001;
static double changePZSpeed = -0.0001;


void DrawPedestrian()
{
	PZXSpeedPos = PZXSpeedPos + changePZSpeed;
	int iSecret, iGuess;

	/* initialize random seed: */
	srand(time(NULL));

	/* generate secret number between 1 and 10: */
	iSecret = rand() % 30 + 1;

	float  Xpos = 0.05 * iSecret;

	//head
	glBegin(GL_POLYGON);
	glColor3f(1, 0.49, 0);
	for (int ii = 0; ii < 100; ii++)
	{
		float theta = 2.0f * 3.1415926f * float(ii) / float(100);//get the current angle

		float z = 0.05 * cosf(theta);//calculate the x component
		float y = 0.05 * sinf(theta);//calculate the y component

		glVertex3f(-1 + Xpos, y + 0.4, z - 0.6 - PZXSpeedPos);//output vertex

	}
	glEnd();

	//body
	glColor3f(0,0, 1);
	glBegin(GL_POLYGON);
	glVertex3f(-1 + Xpos, 0.15, -0.58 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.4, -0.58 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.4, -0.62 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.15, -0.62 - PZXSpeedPos);
	glEnd();

	//arms
	glColor3f(0, 0, 1);
	glBegin(GL_POLYGON);
	glVertex3f(-1 + Xpos, 0.24, -0.53 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.27, -0.53 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.27, -0.67 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.24, -0.67 - PZXSpeedPos);
	glEnd();

	//legs
	glColor3f(0, 0, 1);
	glBegin(GL_POLYGON);
	glVertex3f(-1 + Xpos, 0.15, -0.53 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.10, -0.53 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.10, -0.67 - PZXSpeedPos);
	glVertex3f(-1 + Xpos, 0.15, -0.67 - PZXSpeedPos);
	glEnd();

	if (-PZXSpeedPos > 1)
	{
		PZXSpeedPos = -PZXSpeedPos;
	}
	//std::cout << " iSecret" << iSecret << std::endl;
}


/**
	This is the funtion called at each draw loop.
	We draw a cube and rotate is based on user inputs (arrow keys).
**/
void drawFrame() {
	glMatrixMode(GL_MODELVIEW);
	glLoadIdentity();
	gluLookAt(eyeX, eyeY, eyeZ, centerX, centerY, centerZ, upX, upY, upZ);


	glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

	drawCubeModel();


	//car moving
//	glPushMatrix(); //push a matrix to the stack
//	glTranslated(carObject.x, carObject.y, carObject.z); //translate the square
//	glScaled(carObject.s, carObject.s, 0);

	//Draw ONLY AFTER transformations
	DrawCar();
	DrawPedestrian();

	glFlush();
	glutSwapBuffers();
	glutPostRedisplay();
}


/**
	This function is called each time the mouse is clicked.
**/
void ClickMousePos(int button, int state, int x, int y) {
	if (button == GLUT_LEFT_BUTTON && state == GLUT_DOWN)
	{

	//	std::cout << eyeX << " : " << eyeY << std::endl;

		if (x > 250 && 100 < y && y < 400)
		{
			eyeX += 0.05;
		}
		if (x < 250 && 100 < y && y < 400)
		{
			eyeX -= 0.05;
		}
		if (y > 250 && 100 < x && x < 400)
		{
			eyeY += 0.05;
		}
		if (y < 250 && 100 < x && x < 400)
		{
			eyeY -= 0.05;
		}
	}
}


/**
	Normal key handler
**/
void processNormalKeys(unsigned char key, int x, int y) {
	//escape key is 27
	if (key == 27 || key == 'q' || key == 'Q') {
		exit(0);
	}
}

/**
	Keyboard special key presses.
**/
void processSpecialKeys(int key, int x, int y) {
	switch (key) {
	case GLUT_KEY_LEFT:
		cube.y = (cube.y + cube.speed) % 360;
		break;
	case GLUT_KEY_RIGHT:
		cube.y = (cube.y - cube.speed) % 360;
		break;
	case GLUT_KEY_UP:
		cube.x = (cube.x + cube.speed) % 360;
		break;
	case GLUT_KEY_DOWN:
		cube.x = (cube.x - cube.speed) % 360;
		break;
	}
}


void MenuBar(int v) {
	switch (v) {
	case 1:
		changeSpeed = 0.00002;
		break;
	case 2:
		changeSpeed = 0;
		break;

	case 3:
		changeSpeed = changeSpeed * 2;
		break;

	case 4:
		changeSpeed = changeSpeed / 2;
		break;
	case 5:
		eyeX = 0;
		eyeY = -0.7;
		eyeZ = -0.95;
		centerX = 0;
		centerY = 0;
		centerZ = -1;
		upX = 0;
		upY = 1; 
		upZ = 0;
		 //a camera viewing the whole scene from above the street.
		break;

	case 6:
		eyeX = 0;
		eyeY = 0.2;
		eyeZ = 0;
		centerX = 12;
		centerY = 15;
		centerZ = -12;
		upX = 0;
		upY = 1;
		upZ = 0;
		//normal camera
		break;
	}
}


