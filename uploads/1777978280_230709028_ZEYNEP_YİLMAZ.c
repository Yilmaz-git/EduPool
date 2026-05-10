#include <stdio.h>
#include <string.h>
#include <time.h>

// Global Variables
char materialName[120][60];                   //Keeps material names 
int materialQuantity[120];                   //Keeps material  quantities
char materialExpirationDate[120][11];       //Keep expiration dates 
int materialsNumbers=0;                    //Number of materials on hand 

// Function Prototypes
void openMenu();                         //Open the menu
void addMaterial();                     //Adds material 
void materialRemove();                 //Removes materials 
void listMaterials();                 //Shows material options 
void checkExpirationDates();         //Checks expiration dates 
void suggestRecipes();              //Suggest recipes 
void exitProgram();                //Exit the program 
int solidDate(char date[]);       //Shows the solid date 
void sortMaterials();            //Sort the materials 

int main() {
    int preference;                //Keeps the user's preference 
    checkExpirationDates();       // Check expiration dates on program start

    while (1) {                 //Continues the process until the user terminates it  
        openMenu();
        printf("Please make a choice: ");
        if (scanf("%d", &preference ) != 1) {            // Checks whether the input received from the user is a valid integer
            printf("Invalid choice. Please enter a number between 0-5.\n");
            while (getchar() != '\n');                  // Clear invalid input
            continue;
        }

        switch (preference) {             //Takes action based on the user-selected situation
            case 0: 
			    addMaterial();
			    break;
            case 1: 
			    materialRemove(); 
		    	break;
            case 2: 
			    listMaterials();
			    break;
            case 3: 
			    checkExpirationDates();
		        break;
            case 4: 
			    suggestRecipes(); 
			    break;
            case 5: 
			    exitProgram(); 
			    return 0;
			    
            default: printf("Wrong selection,try again please!\n");
        }
    }
    return 0;
}

// Menu Display
void openMenu() {
    printf("\n=== Smart Refrigerator ===\n");
    printf("0. Add Materials\n");
    printf("1. Remove Material\n");
    printf("2. list Materials\n");
    printf("3. Check Expiration Dates\n");
    printf("4. Suggest Recipes\n");
    printf("5. Exit\n");
}

// Add Material
void addMaterial() {
    char newMaterial[60];
    int newQuantity;
    char newExpirationDate[11];
    int i = 0;

    printf("Material Name: ");
    scanf("%s", newMaterial);
    printf("Quantity: ");
    scanf("%d", &newQuantity);

    while (1) {                          // Starts loop to get current expiration date
        printf("Expiration Date (dd/mm/yyyy): ");
        scanf("%s", newExpirationDate);
        
        if (solidDate(newExpirationDate)) 
		break;
        printf("Invalid date. Try again.\n");
    }

    int found = 0;          //'found' checks whether material is avaliable
    
    // Avaible Material Control
    while (i < materialsNumbers) {                           //Add the material that existed
        if (strcmp(materialName[i], newMaterial) == 0) {    //If it has material then it increases the material quantity it has
            materialQuantity[i] += newQuantity;
            strcpy(materialExpirationDate[i], newExpirationDate);    //Change the expiration date 
            found = 1;                 //Shows that material found
            break;
        }
        i++;
    }

    if (!found) {            //If there is no material found, add new material
        strcpy(materialName[materialsNumbers], newMaterial);
        materialQuantity[materialsNumbers] = newQuantity;
        strcpy(materialExpirationDate[materialsNumbers], newExpirationDate);
        materialsNumbers++;
    }

    sortMaterials();             // Materials are sorted
    printf("Material Increased!\n");
}

// Material Extraction
void materialRemove() {
    char extractedMaterial[60];
    int extractedQuantity;

    printf("Material Name: ");
    scanf("%s", extractedMaterial);
    printf("Amount to be deleted: ");
    scanf("%d", &extractedQuantity);

    int i=0;    
    for (; i < materialsNumbers; i++) {           // If material is found
        if (strcmp(materialName[i], extractedMaterial) == 0) {       //If there is enough material
            if (materialQuantity[i] >= extractedQuantity) {   
                materialQuantity[i] -= extractedQuantity;           // Decrease the quantity
                if (materialQuantity[i] == 0) {   
                    int j = i;
                    for ( ;j < materialsNumbers - 1; j++) {         //Shift operation
                        strcpy(materialName[j], materialName[j + 1]);
                        materialQuantity[j] = materialQuantity[j + 1];
                        strcpy(materialExpirationDate[j], materialExpirationDate[j + 1]);
                    }
                    materialsNumbers--;  
                }
                printf("Material reduced!\n");
                return;
            } else {
                printf("Not enough materials!\n");
                
                return;
            }
        }
    }
    printf("Material not found!\n");
}

// Listing Materials
void listMaterials() {
    int i = 0;

    printf("\n=== Material List ===\n");
    while (i < materialsNumbers) {
        printf("Material Name: %s, Quantity: %d, Expiration Date: %s\n",
               materialName[i], materialQuantity[i], materialExpirationDate[i]);
        i++;
    }
}

// Bubble Sort for Alphabetical Sequence
void sortMaterials() {
    int i = 0;

    while (i < materialsNumbers - 1) {
        int j = 0;

        while (j < materialsNumbers - i - 1) {
            if (strcmp(materialName[j], materialName[j + 1]) > 0) {         // Change material name
                char tentName[60], tentDate[11];                           // Make a temporary function with using 'tentative' 
                int tentQuantity;

                strncpy(tentName, materialName[j], 60);
                strncpy(materialName[j], materialName[j + 1], 60);
                strncpy(materialName[j + 1], tentName, 60);

                tentQuantity = materialQuantity[j];                // Change material quantity
                materialQuantity[j] = materialQuantity[j + 1];
                materialQuantity[j + 1] = tentQuantity;

                strncpy(tentDate, materialExpirationDate[j], 11);          // Change expiration date
                strncpy(materialExpirationDate[j], materialExpirationDate[j + 1], 11);
                strncpy(materialExpirationDate[j + 1], tentDate, 11);
            }
            j++;
        }
        i++;
    }
}

//Check Expiration Date
void checkExpirationDates() {
    time_t t = time(NULL);  
    struct tm tm = *localtime(&t);  
    int currentDay = tm.tm_mday;
    int currentMonth = tm.tm_mon + 1;
    int currentYear = tm.tm_year + 1900;

    printf("\n=== Expired Materials ===\n");
    int i = 0, found = 0;

    while (i < materialsNumbers) {  
        int exptDay, exptMonth, exptYear;
        sscanf(materialExpirationDate[i], "%2d/%2d/%4d", &exptDay, &exptMonth, &exptYear);

        if (exptYear < currentYear ||               // Checks whether the material has expired or not
            (exptYear == currentYear && exptMonth < currentMonth) || 
            (exptYear == currentYear && exptMonth == currentMonth && exptDay < currentDay)) {
            printf("Material Name: %s, Quantity: %d, Expiration Date: %s\n",
                   materialName[i], materialQuantity[i], materialExpirationDate[i]);
            found = 1;
        }
        i++;
    }

    if (!found) {  
        printf("No expired materials found!\n");
    }
}



// Validate Date
int solidDate(char date[]) {
    if (strlen(date) != 10 || date[2] != '/' || date[5] != '/')
	return 0;
	
    int day, month, year;
    sscanf(date, "%d/%d/%d", &day, &month, &year);
    if (day < 1 || day > 31 || month < 1 || month > 12 || year < 1900)     // Checks if day, month and year are valid
	return 0;   
	   
    return 1;
}

// Suggest Recipes
void suggestRecipes() {
    char *recipes[][4] = {
        {"Egg", "Tomato", "Pepper", "Onion"},
        {"Mushroom", "Cream", "Milk", "Cheese"},
        {"Carrot", "Olive", "Lettuce", "Tuna"},
        {"Egg", "Flour", "Butter", "Milk"},
        {"Rice", "Milk", "Sugar", "Cinnamon"},
        {"Honey", "Walnut", "Sugar", "Milk"},
        {"Fish", "Lemon", "Mint", "Onion"},
        {"Rice", "Milk", "Sugar", "Cinnamon"},
        {"Bread","Tomato","Cream Cheese","Cucumber"},
        {"Pasta","Water","Salt","Tomato Paste"},
        {"Steak", "Butter", "Salt", "Pepper"},
    };
    int recipeCount = 11;
    int i = 0;

    printf("\n=== Recipe Suggestions ===\n");

    while (i < recipeCount) {
        int j = 0, match = 1;          // j* checks the ingredient index of the recipe, match* checks whether the entire recipe is available

        while (j < 4) {
            int k = 0, found = 0;      // k* to browse the material list, found* checks whether the material is available

            while (k < materialsNumbers) {      // Use strcasecmp for case-insensitive comparison
                if (strcasecmp(recipes[i][j], materialName[k]) == 0 && materialQuantity[k] > 0) {
                    found = 1;
                    break;
                }
                k++;
            }

            if (!found) {
                match = 0;
                break;
            }

            j++;
        }

        if (match) {
            printf("Recipe %d: %s, %s, %s, %s\n", i + 1, recipes[i][0], recipes[i][1], recipes[i][2], recipes[i][3]);
        }

        i++;
    }
}

// Exit Program
void exitProgram() {
    printf("Exiting the program. Goodbye!\n");
}
