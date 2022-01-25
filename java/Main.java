package algo;

public class Main {

	public static void main(String[] args) {
		Holder[] l=new Holder[7];
		l[0]=new Holder(new Product("louis", null, 0, 0, 0, args), 10);
		l[1]=new Holder(new Product("francis", null, 0, 0, 0, args), 2);
		l[2]=new Holder(new Product("table", null, 0, 0, 0, args), 5);
		l[3]=new Holder(new Product("livre", null, 0, 0, 0, args), 20);
		l[4]=new Holder(new Product("telephone", null, 0, 0, 0, args), 1);
		l[5]=new Holder(new Product("ordinateur", null, 0, 0, 0, args), 36);
		l[6]=new Holder(new Product("souris", null, 0, 0, 0, args), 4);

		MergeSort.mergeSort(l, l.length);
		for(int i=0;i<l.length;i++) {
			System.out.println(l[i].getProduct().getName());
		}
	}
	
	public static Holder[] algo(Product[] p) {
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
	
}
