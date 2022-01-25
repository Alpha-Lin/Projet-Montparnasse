package algo;

public class MergeSort {
	public static void mergeSort(Holder[] a, int n) {
	    if (n < 2) {
	        return;
	    }
	    int mid = n / 2;
	    Holder[] l = new Holder[mid];
	    Holder[] r = new Holder[n - mid];

	    for (int i = 0; i < mid; i++) {
	        l[i] = a[i];
	    }
	    for (int i = mid; i < n; i++) {
	        r[i - mid] = a[i];
	    }

	    mergeSort(l, mid);
	    mergeSort(r, n - mid);
		mergePertinence(a, l, r, mid, n - mid);
	    

	}
	public static void mergeSort(Product[] a, int n, String type, String order) {
	    if (n < 2) {
	        return;
	    }
	    int mid = n / 2;
	    Product[] l = new Product[mid];
	    Product[] r = new Product[n - mid];

	    for (int i = 0; i < mid; i++) {
	        l[i] = a[i];
	    }
	    for (int i = mid; i < n; i++) {
	        r[i - mid] = a[i];
	    }

	    mergeSort(l, mid,type, order);
	    mergeSort(r, n - mid,type, order);

	    if(type.contains("date")) {
		    mergeDate(a, l, r, mid, n - mid, order);
	    }
	    else if(type.contains("prix")) {
		    mergePrice(a, l, r, mid, n - mid, order);
		}

	}
	public static void mergeDate(Product[] a, Product[] l,Product[] r, int left, int right, String order) {
		int i = 0, j = 0, k = 0;
		while (i < left && j < right) {
			if(order.contains("c")) {
				if (l[i].getDate() <= r[j].getDate()) {
				    a[k++] = l[i++];
				}
				else {
				    a[k++] = r[j++];
				}
			}
			else {
				if (l[i].getDate() <= r[j].getDate()) {
				    a[k++] = r[j++];
				}
				else {
				    a[k++] = l[i++];
				}
			}


		}
		while (i < left) {
			a[k++] = l[i++];
		}
		while (j < right) {
			a[k++] = r[j++];
		}
	}
	public static void mergePrice(Product[] a, Product[] l,Product[] r, int left, int right, String order) {
		int i = 0, j = 0, k = 0;
		while (i < left && j < right) {
			if(order.contains("c")) {
				if (l[i].getPrice() <= r[j].getPrice()) {
				    a[k++] = l[i++];
				}
				else {
				    a[k++] = r[j++];
				}
			}
			else {
				if (l[i].getPrice() >= r[j].getPrice()) {
				    a[k++] = l[i++];
				}
				else {
				    a[k++] = r[j++];
				}
			}

		}
		while (i < left) {
			a[k++] = l[i++];
		}
		while (j < right) {
			a[k++] = r[j++];
		}
	}
	public static void mergePertinence(Holder[] a, Holder[] l,Holder[] r, int left, int right) {
		int i = 0, j = 0, k = 0;
		while (i < left && j < right) {
			if (l[i].getScore() <= r[j].getScore()) {
			    a[k++] = l[i++];
			}
			else {
			    a[k++] = r[j++];
			}
		}
		while (i < left) {
			a[k++] = l[i++];
		}
		while (j < right) {
			a[k++] = r[j++];
		}
	}
}
