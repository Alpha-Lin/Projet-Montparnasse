package algo;

import java.util.Collections;

public class Main {

	public static void main(String[] args) {
		/*Holder[] l=new Holder[7];
		Product p1=new Product("louis", null, 1204, 0, 0, args,5);
		Product p2=new Product("table", null, 1200, 0, 0, args,25);
		Product p3=new Product("livre", null, 120, 0, 0, args,1);
		Product p4=new Product("telephone", null, 3500, 0, 0, args,250);
		Product p5=new Product("ordinateur", null, 12, 0, 0, args,500);
		Product p6=new Product("souris", null, 20, 0, 0, args,15);
		Product p7=new Product("etagere", null, 3, 0, 0, args,50);
		Product[] p=new Product[7];
		p[0]=p1;
		p[1]=p2;
		p[2]=p3;
		p[3]=p4;
		p[4]=p5;
		p[5]=p6;
		p[6]=p7;
		

		
		Holder[] h=algoPertinence(p);
		System.out.println();
		System.out.println("pertinence");
		for(int i=0;i<h.length;i++) {
			System.out.println(h[i].getProduct().getName());
		}
		System.out.println();
		System.out.println("date d");

		algoDate(p, "d");
		for(int i=0;i<l.length;i++) {
			System.out.println(p[i].getName());
		}
		System.out.println();
		System.out.println("date c");
		algoDate(p, "c");
		for(int i=0;i<l.length;i++) {
			System.out.println(p[i].getName());
		}
		System.out.println();
		System.out.println("prix d");
		algoPrix(p, "d");
		for(int i=0;i<l.length;i++) {
			System.out.println(p[i].getName());
		}
		System.out.println();
		System.out.println("prix c");
		algoPrix(p, "c");
		for(int i=0;i<l.length;i++) {
			System.out.println(p[i].getName());
		}
		System.out.println();
		System.out.println("random");
		algoRandom(p);
		for(int i=0;i<l.length;i++) {
			System.out.println(p[i].getName());
		}*/
	}
	
	public static Holder[] algoPertinence(Product[] p) {
		float referencmentVariable=1; //variable definicant le bonus attribué à l'achat de réferencement
		float tagVariable=1; //variable definicant le bonus attribué aux tag correspondant
		float dateVariable=1; //variable definicant le bonus attribué à la date 
		float malusNonMatchingTag=1; //variable definicant le bonus attribué aux tags differants
		String researchNonSplited= "sac a dos"; //barre de recherche
		
		
		String[] research=researchNonSplited.split(" ");
		Holder[] listProduct=new Holder[p.length];
		for(int i=0;i<p.length;i++) {
			float score=0;
			Product pi=p[i];
			score+=pi.getReferencement()*referencmentVariable;
			score+=numberTagMatching(pi.getTag(), research)*tagVariable;
			long d=avantageDate(pi.getDate());
			if(research.length-d!=0) {
				score+=d*dateVariable/(research.length-d)*malusNonMatchingTag;
			}
			else {
				score+=d*dateVariable;
			}
			
			listProduct[i]=new Holder(p[i], score);
		}
		MergeSort.mergeSort(listProduct, listProduct.length);
		return listProduct;
		
	}
	public static int numberTagMatching(String[] l, String[] research) {
		int somme=0;
		for(int i=0;i<l.length;i++) {
			Boolean haveBeenSpoted=false;
			for(int j=0;j<research.length;j++) {
				if(l[i].contains(research[j])&&!haveBeenSpoted) {
					somme++;
					haveBeenSpoted=true;
				}
			}
		}
		return somme;
	}
	public static long avantageDate(long Date) {
		long currentDate=System.currentTimeMillis();
		return currentDate-Date;
	}
	public static Product[] algoDate(Product[] p, String order) {
		
		MergeSort.mergeSort(p, p.length,"date",order);
		return p;
	}
	public static Product[] algoRandom(Product[] p) {
		for(int i=0;i<p.length;i++) {
			int x=(int) (Math.random()*p.length);
			Product pi=p[i];
			p[i]=p[x];
			p[x]=pi;
		}
	
		return p;
	}
	public static Product[] algoPrix(Product[] p, String order) {
		MergeSort.mergeSort(p, p.length, "prix",order);
		return p;
	}

}
