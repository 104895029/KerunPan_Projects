/***
        COMP-3520 A2
        SID: Kerun Pan
        SNUM: 104895029
        Functionality:     
        10. Right click the mouse (on window)a red predator at a location
        11. Left click the mouse to place a green prey at a location
***/

/*
*/
#include <GLFW/glfw3.h>
#include <stdlib.h>
#include <stdio.h>
#include <iostream>

/* Global Variables to handle movements */

#define initial_W 500
#define initial_H 500

int W = 500; //buffer's width
int H = 500; //buffer's height
int X = W;         //computer's window width
int Y = H;         //computer's window height
static bool is_prey_placed = false;
static bool is_predator_placed = false;
static bool is_prey_escaped = false;
static bool is_predator_reache = false;

struct coords {
    int x;            //initial position (top left corner)
    int y;
    int s;            //width in pixels
    int speed;        //object speed
    float c1, c2, c3; //RGB colour
} prey = { -100, -100, 10, 1, 0, 0, 0 }, //where make movements here
reset = { initial_W / 2 + 50, initial_H / 2 + 50, 10, 6, 0, 0, 0 }, //a copy of the original coordinates
predator = { -100, -100, 10, 2, 0, 0, 0 }, //second square
reset2 = { initial_W / 2 - 50, initial_H / 2 - 50, 10, 6, 0, 0, 0 }; //reset information of second square

void checkSquareLocation(int, int);
void checkIsPreyEscape();
void checkIsPredatorReache();
void place_prey_in_path(int * mouseX, int * mouseY);
void place_predator_in_path(int* mouseX, int* mouseY);
void drawFrame();
void drawPathLine();
void drawPrey();
void drawPredator();
void preyMoving();
void predatorMoving();
void mouse_button_callback(GLFWwindow*, int, int, int);
static void left_key_place_prey(GLFWwindow*, double, double);
static void right_key_place_predator(GLFWwindow*, double, double);


int main(void)
{

    if (!glfwInit())
        exit(EXIT_FAILURE);

    GLFWwindow* window = glfwCreateWindow(W, H, "Moving Square", NULL, NULL);

    if (!window)
    {
        glfwTerminate();
        exit(EXIT_FAILURE);
    }

    glfwMakeContextCurrent(window);
    glfwSwapInterval(1);

    glfwSetMouseButtonCallback(window, mouse_button_callback);

    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);
    glViewport(0, 0, (GLsizei)W, (GLsizei)H);
    glMatrixMode(GL_PROJECTION);
    glLoadIdentity();
    glOrtho(0.0, W, 0.0, H, 0.0, 1.0);
    glClearColor(1.0, 1.0, 1.0, 0.0);

    //Save the computer's pixel height / width
    glfwGetFramebufferSize(window, &X, &Y);

    while (!glfwWindowShouldClose(window))
    {

        drawFrame();


        glfwSwapBuffers(window);
        glfwPollEvents();
    }

    glfwDestroyWindow(window);
    glfwTerminate();
    exit(EXIT_SUCCESS);
}



void checkSquareLocation(int w, int h) {
    if (prey.s > w)
        prey.s = w;
    if (prey.s > h)
        prey.s = h;
    if (prey.s < 2)
        prey.s = 2;
    if (prey.x + prey.s > w)
        prey.x = w - prey.s;
    if (prey.y + prey.s > h)
        prey.y = h - prey.s;

    if (predator.s > w)
        predator.s = w;
    if (predator.s > h)
        predator.s = h;
    if (predator.s < 2)
        predator.s = 2;
    if (predator.x + predator.s > w)
        predator.x = w - predator.s;
    if (predator.y + predator.s > h)
        predator.y = h - predator.s;
}


void place_prey_in_path(int *mouseX, int * mouseY)
{
   
    if (*mouseX >= 110 && *mouseX <= 390)
    {
        if (*mouseY >= 90 && *mouseY <= 110)//case 1 , place in row, 110<=x<=390, 90<= y <=110 , y = 100
        {
            *mouseY = 105;
        }

        if (*mouseY >= 390 && *mouseY <= 410) //case 2 , place in row, 110<=x<=390, 390<= y <=410, y = 400
        {
            *mouseY = 405;
        }
    }
   
    if (*mouseX >= 90 && *mouseX <= 110) //case 3 , place in col, 90<= x<= 110, x = 100
    {
        *mouseX = 95;
    }

    if (*mouseX >= 390 && *mouseX <= 410)//case 4  , place in col, 390<= x<= 410. x = 400
    {
        *mouseX = 395;
    }
}

void place_predator_in_path(int* mouseX, int* mouseY)
{

    if (*mouseX >= 110 && *mouseX <= 390)
    {
        if (*mouseY >= 90 && *mouseY <= 110)//case 1 , place in row, 110<=x<=390, 90<= y <=110 , y = 100
        {
            *mouseY = 110;
        }

        if (*mouseY >= 390 && *mouseY <= 410) //case 2 , place in row, 110<=x<=390, 390<= y <=410, y = 400
        {
            *mouseY = 410;
        }
    }

    if (*mouseX >= 90 && *mouseX <= 110) //case 3 , place in col, 90<= x<= 110, x = 100
    {
        *mouseX = 90;
    }

    if (*mouseX >= 390 && *mouseX <= 410)//case 4  , place in col, 390<= x<= 410. x = 400
    {
        *mouseX = 390;
    }
}

/**
        This is our only draw function.
        We draw a 1x1 pixel square at position (0, 0).
        We will translate and scale it as needed.
**/

void drawPrey()
{
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
}

void drawPredator()
{

    if (predator.x >= 110 && predator.x <= 390)
    {
        if (predator.y >= 90 && predator.y <= 110)//case 1 , place in row, 110<=x<=390, 90<= y <=110 , y = 100
        {
            glColor3f(1.0f, 0.0f, 0.0f);
            glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);
            glBegin(GL_POLYGON);
            glVertex2i(0, 0);
            glVertex2i(0, 2);
            glVertex2i(1, 1);
            glEnd();

        }

        if (predator.y >= 390 && predator.y <= 410) //case 2 , place in row, 110<=x<=390, 390<= y <=410, y = 400
        {
            glColor3f(1.0f, 0.0f, 0.0f);
            glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);
            glBegin(GL_POLYGON);
            glVertex2i(0, 0);
            glVertex2i(-1, 1);
            glVertex2i(0, 2);
            glEnd();
        }
    }

    if (predator.x >= 90 && predator.x <= 110) //case 3 , place in col, 90<= x<= 110, x = 100
    {
        glColor3f(1.0f, 0.0f, 0.0f);
        glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);
        glBegin(GL_POLYGON);
        glVertex2i(0, 0);
        glVertex2i(1, -1);
        glVertex2i(2, 0);
        glEnd();
    }

    if (predator.x >= 390 && predator.x <= 410)//case 4  , place in col, 390<= x<= 410. x = 400
    {
        glColor3f(1.0f, 0.0f, 0.0f);
        glPolygonMode(GL_FRONT_AND_BACK, GL_FILL);
        glBegin(GL_POLYGON);
        glVertex2i(0, 0);
        glVertex2i(1, 1);
        glVertex2i(2, 0);
        glEnd();
    }
}

void preyMoving()
{
    if (prey.x >= 90 && prey.x <= 394)
    {      
        
        if (prey.y >= 90 && prey.y <= 95)//case 2 , place in row
        {
            prey.x += prey.speed;
        //    std::cout << "case 2" << std::endl;
        }
    }

    if (prey.x >= 96 && prey.x <= 410)
    {
        if (prey.y >= 395 && prey.y <= 410) //case 1 , place in row
        {
            prey.x -= prey.speed;
        //    std::cout << "case 1" << std::endl;
        }
    }

    if (prey.x >= 90 && prey.x <= 95) //case 3 , place in col
    {
      //  prey.x = 95;     
        if (prey.y >= 96 && prey.y <= 410)
        {
            prey.y -= prey.speed;
        //    std::cout << "case 3"  << std::endl;           
        }
    }

    if (prey.x >= 395 && prey.x <= 410)//case 4  , place in col
    {      
    //    prey.x = 95;
        if (prey.y > 90 && prey.y <= 394)
        {
            prey.y += prey.speed;
        //    std::cout << "case 4" << std::endl;
        }
    }
}

void predatorMoving()
{
    if (predator.x >= 90 && predator.x < 390)
    {

        if (predator.y >= 90 && predator.y <= 91)//case 2 , place in row
        {
      //      std::cout << predator.x << " : " << predator.y << std::endl;
            predator.x += predator.speed;
           // std::cout << "case 2" << std::endl;
        }
    }

    if (predator.x >= 92 && predator.x <= 410)
    {
        // prey.y = 95;
        if (predator.y >= 390 && predator.y <= 410) //case 1 , place in row
        {
            predator.x -= predator.speed;
         //   std::cout << "case 1" << std::endl;
        }
    }

    if (predator.x >= 90 && predator.x <= 91) //case 3 , place in col
    {
        //  prey.x = 95;     
        if (predator.y >= 92 && predator.y <= 410)
        {
            predator.y -= predator.speed;
        //    std::cout << "case 3" << std::endl;
        }
    }

    if (predator.x >= 390 && predator.x <= 410)//case 4  , place in col
    {
        //    prey.x = 95;
        if (predator.y >= 90 && predator.y <= 390)
        {
            predator.y += predator.speed;
         //   std::cout << "case 4" << std::endl;
        }
    }
}
/**
        This is the funtion called at each draw loop
        We draw 2 squares using drawSquareModel().
        Each square gets a different MODELVIEW matrix.
        This matrix is translated / rotated depending on their
        respective square.
        This will allow us to move the squares independently as needed.
**/

void drawPathLine() 
{
    glLineWidth(1.0);
    glColor3f(0.0f, 0.0f, 1.0f);

    glPolygonMode(GL_FRONT_AND_BACK, GL_LINE);
    glBegin(GL_POLYGON);
    
    glVertex2i(100, 100);
    glVertex2i(100, 400);
    glVertex2i(400, 400);
    glVertex2i(400, 100);
    glEnd();
}

void drawPathBorder()
{
    glLineWidth(3.0);
    glColor3f(0.0f, 0.0f, 0.0f);

    glPolygonMode(GL_FRONT_AND_BACK, GL_LINE);
    glBegin(GL_POLYGON);

    glVertex2i(440, 240);
    glVertex2i(410, 240);
    glVertex2i(410, 90);
    glVertex2i(90, 90);
    glVertex2i(90, 410);
    glVertex2i(410, 410);
    glVertex2i(410, 260);
    glVertex2i(440, 260);
    
    glEnd();

    glPolygonMode(GL_FRONT_AND_BACK, GL_LINE);
    glBegin(GL_POLYGON);

    glVertex2i(110, 110);
    glVertex2i(110, 390);
    glVertex2i(390, 390);
    glVertex2i(390, 110);
    glEnd();
}

void checkIsPreyEscape()
{
    if (is_predator_placed == true)
    {
      //  std::cout << prey.x << " : " << prey.y << std::endl;
        if (prey.x >= 390 && prey.x <= 410)
        {
         //   std::cout << "prey.x >= 390 && prey.x <= 410" << std::endl;
            if (prey.y >= 245 && prey.y <= 255)
            {
      //          std::cout << "prey.y >= 245 && prey.y <= 255" << std::endl;
                is_prey_escaped = true;
            }
        }

        if (is_prey_escaped == true) // If the prey escapes, the predator gets a bit smalled in size and stops moving
        {
            prey.x += 4;
            predator.speed = 0;
            predator.s = 5;
        }
    }
}

void checkIsPredatorReache()
{
    if (is_predator_placed == true)
    {
        if (abs(prey.x - predator.x) <= 5)
        {
            //   std::cout << "prey.x >= 390 && prey.x <= 410" << std::endl;
            if (abs(prey.y - predator.y) <= 5)
            {

                is_predator_reache = true;
            }
        }

        if (is_predator_reache == true) // If the predator reache the prey, it will eat it, that is, the prey vanishes and the predator gets a bit bigger in sizeand stops moving
        {
         //   std::cout << "is_predator_reache == true" << std::endl;
            prey.s = 0;
            predator.speed = 0;
            predator.s = 15;
        }
    }
}

void drawFrame() {
    glMatrixMode(GL_MODELVIEW);
    glLoadIdentity();
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    drawPathBorder();
    drawPathLine();
   
    glColor3f(prey.c1, prey.c2, prey.c3);

    //Everything is applied to current matrix
    //(located at the top of the stack, hence the use of push)
    glPushMatrix(); //push a matrix to the stack
    glTranslated(prey.x, prey.y, 0); //translate the square
    glScaled(prey.s, prey.s, 0);

    //Draw ONLY AFTER transformations
    drawPrey();
    preyMoving();

    //"pop" the matrix and continue with the next (independent) object
    glPopMatrix(); //remove object from top of stack

    /*Draw predator */
    glColor3f(predator.c1, predator.c2, predator.c3);
    glPushMatrix();
    glTranslated(predator.x, predator.y, 0);
    glScaled(predator.s, predator.s, 0);
    drawPredator();
    predatorMoving();
    glPopMatrix();

    checkIsPreyEscape();
    checkIsPredatorReache();

    glFlush(); //draw frame
}



/**
        This function is called each time the mouse is clicked.
        We handle left/right mouse clicks and actions there.
**/
void mouse_button_callback(GLFWwindow* window, int button, int action, int mods) {

    //11. Left click the mouse to place a green prey at a location
    if (button == GLFW_MOUSE_BUTTON_LEFT && action == GLFW_PRESS && is_prey_placed == false) {

        is_prey_placed = true;

        double xpos, ypos;
        glfwGetCursorPos(window, &xpos, &ypos);

        left_key_place_prey(window, xpos, ypos);

        /*
                if (square.s < 100 && square.x + square.s + 1 < W && square.y + square.s + 1 < H)
            square.s += 1;
        if (square2.s < 100 && square2.x + square2.s + 1 < W && square2.y + square2.s + 1 < H)
            square2.s += 1;
        */

    }
    // 10. Right click the mouse (on window)a red predator at a location
    if (button == GLFW_MOUSE_BUTTON_RIGHT && action == GLFW_PRESS && is_predator_placed == false && is_prey_placed == true)
    {
            is_predator_placed = true;
         
            double xpos, ypos;
            glfwGetCursorPos(window, &xpos, &ypos);

            right_key_place_predator(window, xpos, ypos);        
            /*           
            if (square.s > 5)
                square.s -= 1;
            if (square2.s > 5)
                square2.s -= 1;
            */
    }
}

/**
   get Mouse position
**/
static void left_key_place_prey(GLFWwindow* window, double xpos, double ypos)
{
    int iXpos = (int)xpos;
    int iypos = (int)ypos;
    place_prey_in_path(&iXpos, &iypos);
    prey.x = iXpos;
    prey.y = initial_H - iypos;
    std::cout << iXpos << " : " << iypos << std::endl;
}

static void right_key_place_predator(GLFWwindow* window, double xpos, double ypos)
{
    int iXpos = (int)xpos;
    int iypos = (int)ypos;
    place_predator_in_path(&iXpos, &iypos);
    predator.x = iXpos;
    predator.y = initial_H - iypos;
    std::cout << iXpos << " : " << iypos << std::endl;
}