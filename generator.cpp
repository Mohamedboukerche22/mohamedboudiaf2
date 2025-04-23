/*for i in range(3):
    for j in range (5):
        print(f"<option value="{i}s{j}">{i}s{j}</option>")
        */




#include<bits/stdc++.h>
using namespace std;
int main(){
   int n;
   cin >>n;
 vector<string>lol(n);
 for(string &i:lol)cin >>i;
 for(int i=0;i<n;i++){
cout <<"<option value="<<lol[i]<<'"'<<lol[i]<<"</option>"<<endl;
 }
 
}
